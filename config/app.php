<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

return [
    'name' => $_ENV['APP_NAME'],
    'env' => $_ENV['APP_ENV'],
    'debug' => $_ENV['APP_DEBUG'],
    'url' => $_ENV['APP_URL'],
    'display_errors' => $_ENV['APP_DEBUG'],
    'log_path' => __DIR__ . '/../storage/logs'
];
