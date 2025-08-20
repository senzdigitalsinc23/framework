<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Cache;
use App\Core\Config;
use App\Core\Container;
use App\Core\EventDispatcher;
use App\Core\Queue;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\Storage;


Config::load(dirname(__DIR__) . '/config');

if (session_status() === PHP_SESSION_NONE) {
    // Secure cookie & strict session settings
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? null) == 443;

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax', // consider 'Strict' for non-3rd-party flows
    ]);

    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', $secure ? '1' : '0');
    ini_set('session.use_only_cookies', '1');
    session_name('app_session');

    session_start();
    // Rotate session ID after login or privilege changes
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

// public/index.php (top-level front controller)
set_exception_handler(function (\Throwable $e) {
    $isApi = str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/api/');
    $code  = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

    http_response_code($code);

    //show(Config::get('app.debug'));

    if (!empty(Config::get('app.debug')) && Config::get('app.debug') === 'true') {
        if ($isApi) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
        } else {
            echo "<pre>" . htmlspecialchars((string)$e, ENT_QUOTES) . "</pre>";
        }
    } else {
        if ($isApi) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Server error']);
        } else {
            echo "Something went wrong.";
        }
    }
});

// Boot container
$container = new Container();

$app_name = '';

// Bind core services
$container->singleton(Request::class, fn() => new Request());
$container->singleton(Response::class, fn() => new Response());

// Register Cache (singleton)
$container->singleton(Cache::class, function () {
    return new Cache(__DIR__ . '/../storage/cache');
});

// Register Storage (singleton)
$container->singleton(Storage::class, function () {
    return new Storage(__DIR__ . '/../storage/files');
});

$container->singleton(Queue::class, function () {
    return new Queue(__DIR__ . '/../storage/jobs');
});




/* $container->singleton(EmailService::class, function () {
    return new EmailService('noreply@myapp.com');
});

$container->singleton(SMSService::class, function () {
    return new SMSService();
}); */

$container->singleton(EventDispatcher::class, function () {
    return new EventDispatcher();
});

// Init router
$router = new Router($container);

// Load routes
require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/api.php';

// Dispatch request
$request = $container->resolve(Request::class);
$response = $container->resolve(Response::class);

/* $sms = $container->resolve(SMSService::class); */

$response = $router->dispatch($request, $response);
$response->send();

