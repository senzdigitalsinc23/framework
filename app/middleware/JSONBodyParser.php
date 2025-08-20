<?php
// app/Middleware/JsonBodyParserMiddleware.php
namespace App\Middleware;

class JsonBodyParser
{
    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array($method, ['POST','PUT','PATCH'])) {
            $ctype = $_SERVER['CONTENT_TYPE'] ?? '';
            if (stripos($ctype, 'application/json') !== false) {
                $raw = file_get_contents('php://input');
                $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
                $_POST = is_array($data) ? $data : [];
            }
        }
    }
}
