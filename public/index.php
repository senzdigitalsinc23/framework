<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Cache;
use App\Core\Container;
use App\Core\EventDispatcher;
use App\Core\Queue;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\Storage;

session_start();
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

