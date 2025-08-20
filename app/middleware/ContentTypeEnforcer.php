<?php
// app/Middleware/ContentTypeEnforcerMiddleware.php
namespace App\Middleware;

class ContentTypeEnforcer
{
    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array($method, ['POST','PUT','PATCH'])) {
            $ctype = $_SERVER['CONTENT_TYPE'] ?? '';
            // Accept JSON or form-encoded only
            if (
                stripos($ctype, 'application/json') === false &&
                stripos($ctype, 'application/x-www-form-urlencoded') === false &&
                stripos($ctype, 'multipart/form-data') === false
            ) {
                http_response_code(415);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Unsupported Media Type']);
                exit;
            }
        }
    }
}
