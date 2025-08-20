<?php
// app/Middleware/ApiKeyMiddleware.php
namespace App\Middleware;

use App\Core\Config;
use App\Core\Request;
use App\Core\Response;

class APIKeyMiddleware
{
    private array $validKeys;

    public function __construct()
    {
        // Load config PHP files
        Config::load(dirname(__DIR__) . '/config');

        // Load keys from .env
        $keys = Config::get('api.key') ?? '';

        $this->validKeys = array_map('trim', explode(',', $keys));
    }

    public function handle(Request $request, Response $response, callable $next): Response
    {
        $key = $_SERVER['QUERY_STRING'] ?? $_GET['api_key'] ?? '';
        $secret = $_ENV['API_SECRET'];

        //show($key);

        if (!$key || !in_array(explode('=', $key)[1], $this->validKeys, true)) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid api key authentication']);
            exit;
        }
        
        return $next($request, $response);
    }

    
}

/* <?php
// app/Middleware/ApiKeyMiddleware.php
namespace App\Middleware;

use App\Core\Config;
use App\Core\Request;
use App\Core\Response;
use Repositories\ApiKeyRepo;
use Support\ApiPath;

class ApiKeyMiddleware
{
    protected ApiKeyRepo $repo;

    public function __construct() {
        // Load config PHP files
        Config::load(dirname(__DIR__) . '/config');
        $this->repo = new ApiKeyRepo();
    }

    public function handle(Request $request, Response $response, callable $next): Response
    {
        //show(ApiPath::isApi());
        if (!ApiPath::isApi() || ApiPath::isWhitelisted()) {
            return $next($request, $response);//echo json_encode(['success' => false, 'message' => 'Missing API key'], 401);exit; // only protect /api/* and not whitelisted paths
        }

        //show(Config::get('api.key'));
        $key = Config::get('api.key') ?? ($_GET['api_key'] ?? '');
        
        if (!$key) {
            return $response()->json(['success' => false, 'message' => 'Missing API key'], 401);
            //$this->deny('Missing API key'); // 401
        }

        $record = $this->repo->find($key);

        //show($this->repo->isValid($record));

        //show($this->repo->isValid($record));
        if (!$record || !$this->repo->isValid($record)) {
            return $response()->json(['success' => false, 'message' => 'Invalid or inactive API key'], 401);
            //$this->deny('Invalid or inactive API key'); // 401
        }

        // Attach key metadata for downstream use (logging, scopes, owner)
        $_SERVER['API_KEY_OWNER'] = $record['owner'] ?? null;
        $_SERVER['API_KEY_SCOPES'] = $record['scopes'] ?? [];

        return $next($request, $response);
    }

    /* private function deny(, string $msg): Response
    {
        http_response_code(401);
        header('Content-Type: application/json');
        header('WWW-Authenticate: ApiKey realm="API", format="X-API-KEY: <key>"');
        return $response()->json(['success' => false, 'message' => $msg]);
        exit;
    } */
