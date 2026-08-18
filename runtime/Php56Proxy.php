<?php
declare(strict_types=1);

namespace VbSysLib;

/**
 * Coeur de la cohabitation "PHP moderne -> PHP 5.6 legacy".
 *
 * Reçoit une requête HTTP (déjà décodée) et :
 *  - sert directement les fichiers statiques (images, css, js, zip, ico) ;
 *  - exécute les scripts PHP du site via `php-cgi` 5.6 (SAPI CGI), en passant
 *    la requête par les variables d'environnement CGI et le corps par stdin,
 *    puis parse la sortie (en-têtes + corps).
 *
 * Cette classe tourne sous le PHP moderne (8.x) porté par Bref. Elle est
 * partagée par le handler Lambda (runtime/handler.php) et le serveur de test
 * local (runtime/local-server.php) : la même logique est validée hors AWS.
 */
final class Php56Proxy
{
    /** @var string Racine du site (documents). */
    private $docRoot;
    /** @var string Binaire php-cgi 5.6. */
    private $phpCgi;
    /** @var string Fichier php.ini "legacy". */
    private $phpIni;

    private const STATIC_TYPES = [
        'css' => 'text/css', 'js' => 'application/javascript',
        'gif' => 'image/gif', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png', 'ico' => 'image/x-icon', 'svg' => 'image/svg+xml',
        'zip' => 'application/zip', 'exe' => 'application/octet-stream',
        'txt' => 'text/plain', 'html' => 'text/html', 'htm' => 'text/html',
        'json' => 'application/json',
    ];

    public function __construct(string $docRoot, string $phpCgi, string $phpIni)
    {
        $this->docRoot = rtrim($docRoot, '/');
        $this->phpCgi = $phpCgi;
        $this->phpIni = $phpIni;
    }

    /**
     * @param array<string,string> $headers  En-têtes de requête (clé => valeur)
     * @return array{status:int, headers:array<string,string>, body:string, base64:bool}
     */
    public function handle(string $method, string $path, string $query, array $headers, string $body): array
    {
        $path = '/' . ltrim(rawurldecode($path), '/');

        // Résolution du fichier cible, en interdisant la sortie de la racine.
        $target = $this->resolve($path);
        if ($target === null) {
            return $this->plain(404, "404 Not Found");
        }

        $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        if ($ext !== 'php') {
            return $this->serveStatic($target, $ext);
        }
        return $this->runCgi($method, $path, $query, $headers, $body, $target);
    }

    /** Résout un chemin URL en fichier réel dans la racine (ou null). */
    private function resolve(string $path): ?string
    {
        $candidate = $this->docRoot . $path;
        if (is_dir($candidate)) {
            $candidate = rtrim($candidate, '/') . '/index.php';
        }
        $real = realpath($candidate);
        if ($real === false || strpos($real, $this->docRoot . '/') !== 0 && $real !== $this->docRoot) {
            return null;
        }
        return is_file($real) ? $real : null;
    }

    /** @return array{status:int, headers:array<string,string>, body:string, base64:bool} */
    private function serveStatic(string $file, string $ext): array
    {
        $type = self::STATIC_TYPES[$ext] ?? 'application/octet-stream';
        $data = (string) file_get_contents($file);
        $isText = str_starts_with($type, 'text/') || $type === 'application/javascript' || $type === 'application/json';
        return [
            'status' => 200,
            'headers' => ['Content-Type' => $type . ($isText ? '; charset=iso-8859-1' : '')],
            'body' => $isText ? $data : base64_encode($data),
            'base64' => !$isText,
        ];
    }

    /** Exécute php-cgi 5.6 et parse sa sortie. */
    private function runCgi(string $method, string $path, string $query, array $headers, string $body, string $scriptFile): array
    {
        $env = [
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'REDIRECT_STATUS'   => '200', // requis par le SAPI CGI (sécurité force_redirect)
            'SERVER_PROTOCOL'   => 'HTTP/1.1',
            'SERVER_SOFTWARE'   => 'vbsyslib-bridge',
            'SERVER_NAME'       => $headers['host'] ?? 'localhost',
            'DOCUMENT_ROOT'     => $this->docRoot,
            'SCRIPT_FILENAME'   => $scriptFile,
            'SCRIPT_NAME'       => $path,
            'REQUEST_URI'       => $path . ($query !== '' ? '?' . $query : ''),
            'REQUEST_METHOD'    => $method,
            'QUERY_STRING'      => $query,
            'REMOTE_ADDR'       => '127.0.0.1',
            'PHPRC'             => $this->phpIni,
            // proc_open remplace tout l'environnement : on préserve la
            // résolution des bibliothèques (Bref fournit libcrypt.so.2 dans
            // /opt/lib, référencé via LD_LIBRARY_PATH) et le PATH.
            'LD_LIBRARY_PATH'   => getenv('LD_LIBRARY_PATH') ?: '',
            'PATH'              => getenv('PATH') ?: '/usr/bin:/bin',
        ];
        if ($body !== '') {
            $env['CONTENT_LENGTH'] = (string) strlen($body);
            $env['CONTENT_TYPE'] = $headers['content-type'] ?? 'application/x-www-form-urlencoded';
        }
        foreach ($headers as $k => $v) {
            $env['HTTP_' . strtoupper(str_replace('-', '_', $k))] = $v;
        }

        $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $proc = proc_open(
            [$this->phpCgi, '-c', $this->phpIni],
            $descriptors, $pipes, $this->docRoot, $env
        );
        if (!is_resource($proc)) {
            return $this->plain(500, '500 - php-cgi introuvable');
        }
        fwrite($pipes[0], $body);
        fclose($pipes[0]);
        $out = stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        return $this->parseCgi((string) $out, (string) $err);
    }

    /** @return array{status:int, headers:array<string,string>, body:string, base64:bool} */
    private function parseCgi(string $out, string $err): array
    {
        $sep = strpos($out, "\r\n\r\n");
        $split = 4;
        if ($sep === false) {
            $sep = strpos($out, "\n\n");
            $split = 2;
        }
        if ($sep === false) {
            return $this->plain(500, "500 - sortie CGI invalide\n" . $err);
        }

        $rawHeaders = substr($out, 0, $sep);
        $bodyOut = substr($out, $sep + $split);

        $status = 200;
        $headers = [];
        foreach (preg_split('/\r\n|\n/', $rawHeaders) as $line) {
            if (!str_contains($line, ':')) {
                continue;
            }
            [$name, $value] = explode(':', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (strcasecmp($name, 'Status') === 0) {
                $status = (int) $value;
                continue;
            }
            $headers[$name] = $value;
        }

        // Le SAPI CGI renvoie Location sans Status : c'est une redirection 302.
        if ($status === 200 && isset($headers['Location'])) {
            $status = 302;
        }

        return ['status' => $status, 'headers' => $headers, 'body' => $bodyOut, 'base64' => false];
    }

    /** @return array{status:int, headers:array<string,string>, body:string, base64:bool} */
    private function plain(int $status, string $message): array
    {
        return [
            'status' => $status,
            'headers' => ['Content-Type' => 'text/plain; charset=utf-8'],
            'body' => $message,
            'base64' => false,
        ];
    }
}
