<?php

use App\Controllers\Web\AdminController;
use App\Controllers\Web\AuthController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| These routes return HTML views or handle browser-based requests.
| Make sure to pass controllers as [ControllerClass::class, 'methodName'].
|--------------------------------------------------------------------------
*/

$router->get('/web', [HomeController::class, 'index']/* , [AuthMiddleware::class] */);
$router->get('/about', [HomeController::class, 'about']);

$router->get('/web/login', [AuthController::class, 'index']);
$router->get('/web/register', [AuthController::class, 'registerForm']/* , [AuthMiddleware::class] */);
$router->get('/web/logout', [AuthController::class, 'logout']/* , [AuthMiddleware::class] */);

$router->get('/web/admin', [AdminController::class, 'index'], [AuthMiddleware::class]);
$router->get('/web/admin/users', [AdminController::class, 'users'], [AuthMiddleware::class]);