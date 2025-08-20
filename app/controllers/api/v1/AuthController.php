<?php

namespace App\Controllers\Api\v1;

use App\Core\Auth;
use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Middleware\AuthMiddleware;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController 
{
    protected string $jwtSecret = 'YOUR_SECRET_KEY';
    protected Response $response;

    public function __construct(Response $response) {
        $this->response = $response;
        isLoggedIn();
        
    }
     public function handle(Request $request, Response $response, callable $next): Response
    {
        if (!Session::get('user')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please log in'], 401);
        }

        return $next($request, $response);
    }

    public function register(Request $request): Response
    {
        $status = '';
        $message = '';
        $code  = '';

        $data = $request->getPost();
        //echo json_encode(['success' => false, 'message' =>$data]);exit;
        //$data = $request->only(['name', 'email', 'password', 'role_id', 'status']);
//echo json_encode(['success' => false, 'message' =>'']);exit;
        $validator = new Validator($data, [
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if (User::where('email', $data['email'])) {
            return response()->json(['success' => false, 'message' => 'Email already taken'], 409);
        }else if ($validator->fails()) {          

            $errors = '';

            foreach ($validator->errors() as $values) {
                foreach ($values as $value) {
                    $errors .= $value . "\n";
                }
            }

            $status = false;
            $message = "<pre>" . $errors . "</pre>";
            $code = 000;

        }else {
            $status = true;
            $message = 'User successfully registered';
            $code = 201;

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            if(isset($data['role'])) { $data['role_id'] = $data['role'];unset($data['role']);}
            if(isset($data['_token'])) {unset($data['_token']);}

            User::create($data);
        } 
        
        return response()->json(['success' => $status, 'message' => $message, 'user' => $data], $code);
        //return $this->jsonResponse(201, ['message' => 'User registered successfully', 'user' => $user]);
    }

    public function login(Request $request): Response
    {
        $data = $request->getPost();
        //echo json_encode($data);exit;
        $user = User::where('email', $data['email']);

        if (!$user || !password_verify($data['password'], $user->password)) {

            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        /* 
        $payload = [
            'iss' => 'yourapp',
            'sub' => $user->id,
            'role' => $user->role_id,
            'iat' => time(),
            'exp' => time() + (60 * 60) // 1 hour
        ];

        $token = JWT::encode($payload, $this->jwtSecret, 'HS256'); */
        $token = '';

        unset($user->password);

        $user = [
            'id' => $user->id, 
            'name' => $user->name,
            'email' => $user->email
        ];
        
        Session::set('user', $user);

        //show(json_encode($_SESSION));

        return response()->json(['success' => true, 'redirect' => '/web/admin', 'user' => $user], 200);exit;
        //return $this->jsonResponse(200, ['token' => $token]);
    }

    public function profile(Request $request): Response
    {
        $user = $request->user; // Set in JWT middleware

        return response()->json(['success' => true, 'user' => $user], 200);
        return $this->jsonResponse(200, ['user' => $user]);
    }

    protected function jsonResponse(int $status, array $data): Response
    {
        $response = new Response();
        $response->setStatusCode($status);
        $response->setHeader('Content-Type', 'application/json');
        $response->setContent(json_encode($data));
        return $response;
    }

    public function me(Request $request, Response $response): Response
    {
        // Assuming user_id is set in session or middleware
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please log in'], 401);
            //return $response()->setStatusCode(401)->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
            //return $response()->setStatusCode(404)->json(['success' => false, 'message' => 'User not found']);
        }

        return $response()->json($user);
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Session::destroy();
        return response()->json(['success' => true, 'redirect' => '/web/login', 'message' => 'Logged out successfully'], 400);
        //return $this->jsonResponse(200, ['success' => true, 'message' => 'Logged out successfully']);
    }
} 