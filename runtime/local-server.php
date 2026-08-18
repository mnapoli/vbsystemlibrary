<?php
declare(strict_types=1);

/**
 * Serveur de test LOCAL de la passerelle "PHP moderne -> PHP 5.6".
 *
 * Utilise le même Php56Proxy que le handler Lambda, mais via le serveur web
 * intégré du PHP moderne. Permet de valider toute la chaîne (routing, statique,
 * exécution php-cgi 5.6, en-têtes, redirections) hors AWS :
 *
 *   php -S 0.0.0.0:8080 runtime/local-server.php
 */

require __DIR__ . '/Php56Proxy.php';

use VbSysLib\Php56Proxy;

$proxy = new Php56Proxy(
    getenv('VBSYSLIB_DOCROOT') ?: '/var/task',
    getenv('VBSYSLIB_PHP56') ?: '/opt/php56/bin/php-cgi',
    getenv('VBSYSLIB_PHP_INI') ?: '/var/task/config/php-legacy.ini'
);

$headers = [];
foreach ($_SERVER as $k => $v) {
    if (strpos($k, 'HTTP_') === 0) {
        $name = strtolower(str_replace('_', '-', substr($k, 5)));
        $headers[$name] = (string) $v;
    }
}
if (isset($_SERVER['CONTENT_TYPE'])) {
    $headers['content-type'] = (string) $_SERVER['CONTENT_TYPE'];
}

$result = $proxy->handle(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
    $_SERVER['QUERY_STRING'] ?? '',
    $headers,
    file_get_contents('php://input') ?: ''
);

http_response_code($result['status']);
foreach ($result['headers'] as $name => $value) {
    header($name . ': ' . $value);
}
echo $result['base64'] ? base64_decode($result['body']) : $result['body'];
