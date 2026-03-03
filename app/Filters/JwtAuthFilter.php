<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\JwtService;

class JwtAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeader('Authorization');
        
        if (!$authHeader) {
            return $this->jsonResponse(false, 'Authorization header not provided', null, 401);
        }
        
        $token = str_replace('Bearer ', '', $authHeader->getValue());
        
        if (!$token) {
            return $this->jsonResponse(false, 'Token not provided', null, 401);
        }
        
        $config = config('Auth');
        $jwtService = new JwtService($config->jwtKey, $config->jwtAlgorithm, $config->jwtExpiry);
        
        $decoded = $jwtService->verifyToken($token);
        
        if (!$decoded) {
            return $this->jsonResponse(false, 'Invalid or expired token', null, 401);
        }
        
        // Add user data to request
        $request->userData = [
            'user_id' => $decoded->user_id,
            'email' => $decoded->email,
            'role_id' => $decoded->role_id,
            'employee_id' => $decoded->employee_id,
        ];
        
        return null;
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
    
    private function jsonResponse(bool $success, string $message, $data = null, int $statusCode = 200)
    {
        $response = service('response');
        $response->setStatusCode($statusCode);
        $response->setJSON([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ]);
        
        return $response;
    }
}
