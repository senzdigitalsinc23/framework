<?php
// app/Middleware/CorsMiddleware.php
namespace App\Middleware;

class CorsMiddleware
{
    private array $allowedOrigins;
    private array $allowedMethods;
    private array $allowedHeaders;

    public function __construct()
    {
        $this->allowedOrigins = array_map('trim', explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? ''));
        $this->allowedMethods = ['GET','POST','PUT','PATCH','DELETE','OPTIONS'];
        $this->allowedHeaders = ['Content-Type','Authorization','X-CSRF-TOKEN'];
    }

    public function handle(): void
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($origin && ($this->allowedOrigins === [''] || in_array($origin, $this->allowedOrigins, true))) {
            header("Access-Control-Allow-Origin: $origin");
            header('Vary: Origin');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Methods: ' . implode(',', $this->allowedMethods));
            header('Access-Control-Allow-Headers: ' . implode(',', $this->allowedHeaders));
            header('Access-Control-Max-Age: 600');
        }

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
            http_response_code(204);
            exit; // preflight
        }
    }
}
