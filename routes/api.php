<?php

use App\Controllers\Api\AdminController;
use App\Controllers\Api\AuthController;
use App\Middleware\AuthMiddleware;
use App\Middleware\JWTMiddleware;

$router->post('/api/register', [AuthController::class, 'register'], [AuthMiddleware::class]);
$router->post('/api/login', [AuthController::class, 'login']);
$router->get('/api/me', [AuthController::class, 'me'], [AuthMiddleware::class]);
$router->get('/api/profile', [AuthController::class, 'profile'], [JWTMiddleware::class]);

$router->get('/api/admin/users', [AdminController::class, 'users'], [AuthMiddleware::class]);