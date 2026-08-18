<?php
declare(strict_types=1);

/**
 * Handler Lambda (Bref, runtime "function" PHP 8.x).
 *
 * Bref porte le PHP moderne et la boucle Lambda Runtime API ; ce handler
 * traduit l'événement HTTP (Function URL / API Gateway HTTP API v2) en appel
 * php-cgi 5.6 via Php56Proxy, puis reconstruit une réponse HTTP Bref.
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Php56Proxy.php';

use Bref\Context\Context;
use Bref\Event\Http\HttpHandler;
use Bref\Event\Http\HttpRequestEvent;
use Bref\Event\Http\HttpResponse;
use VbSysLib\Php56Proxy;

return new class extends HttpHandler {
    /** @var Php56Proxy */
    private $proxy;

    public function __construct()
    {
        $this->proxy = new Php56Proxy(
            getenv('VBSYSLIB_DOCROOT') ?: '/var/task',
            getenv('VBSYSLIB_PHP56') ?: '/opt/php56/bin/php-cgi',
            getenv('VBSYSLIB_PHP_INI') ?: '/var/task/config/php-legacy.ini'
        );
    }

    public function handleRequest(HttpRequestEvent $event, Context $context): HttpResponse
    {
        $headers = [];
        foreach ($event->getHeaders() as $name => $values) {
            $headers[strtolower($name)] = is_array($values) ? implode(',', $values) : (string) $values;
        }

        $result = $this->proxy->handle(
            $event->getMethod(),
            $event->getPath(),
            $event->getQueryString(),
            $headers,
            $event->getBody()
        );

        return new HttpResponse($result['body'], $result['headers'], $result['status']);
    }
};
