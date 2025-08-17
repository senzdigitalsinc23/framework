<?php
namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CSRFMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Response $response, callable $next): Response
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $token = $request->input('_token');
            if (!$token || !Session::verifyToken($token)) {
                return $response()->setStatusCode(419)
                    ->setContent('CSRF token mismatch.');
            }
        }

        return $next($request, $response);
    }
}
