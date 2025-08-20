<?php

use App\Controllers\Api\DocumentationController;
use App\Controllers\Api\v1\AdminController;
use App\Controllers\Api\v1\AuthController;
use App\Middleware\AuthMiddleware;
use App\Middleware\ContentTypeEnforcer;
use App\Middleware\CorsMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\JsonBodyParser;
use App\Middleware\JWTMiddleware;
use App\Middleware\SecurityHeaders;

// Global
/* $router->middleware([
    SecurityHeaders::class,
    CorsMiddleware::class,                  // enable only for API frontends you control
    ContentTypeEnforcer::class,
    JsonBodyParser::class,
    CsrfMiddleware::class                   // we added earlier
]); */
/* $router->middleware([\App\Middleware\CsrfMiddleware::class]); */

$router->post('/api/register', [AuthController::class, 'register'], [AuthMiddleware::class]);
$router->post('/api/login', [AuthController::class, 'login']);
$router->get('/api/me', [AuthController::class, 'me'], [AuthMiddleware::class]);
$router->get('/api/logout', [AuthController::class, 'me'], [AuthMiddleware::class]);
$router->get('/api/profile', [AuthController::class, 'profile'], [JWTMiddleware::class]);

$router->get('/api/admin/users', [AdminController::class, 'users'], [AuthMiddleware::class]);


//Documentation endpoints
/* $router->get('/api/swagger', [DocumentationController::class, 'index']);
$router->get('/api/docs', function () {
    include __DIR__ . '/../views/layouts/docs-ui.php'; // The HTML with SwaggerUIBundle
}); */