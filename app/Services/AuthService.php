<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\EmployeeModel;

class AuthService
{
    private $userModel;
    private $jwtService;
    private $auditLogService;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $config = config('Auth');
        $this->jwtService = new \App\Services\JwtService(
            $config->jwtKey, 
            $config->jwtAlgorithm, 
            $config->jwtExpiry
        );
        $this->auditLogService = new AuditLogService();
    }
    
    /**
     * Login with email and password
     */
    public function login(string $email, string $password): array
    {
        $user = $this->userModel->getByEmail($email);
        
        if (!$user) {
            $this->auditLogService->logLogin(0, $email, false);
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        }
        
        if (!$user['is_active']) {
            $this->auditLogService->logLogin($user['id'], $email, false);
            return [
                'success' => false,
                'message' => 'Account is deactivated'
            ];
        }
        
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            $this->auditLogService->logLogin($user['id'], $email, false);
            return [
                'success' => false,
                'message' => 'Invalid email or password'
            ];
        }
        
        // Update last login
        $this->userModel->updateLastLogin($user['id']);
        
        // Get employee data
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($user['employee_id']);
        
        // Log successful login
        $this->auditLogService->logLogin($user['id'], $email, true);
        
        // Generate tokens
        $token = $this->jwtService->generateToken([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'employee_id' => $user['employee_id'],
        ]);
        
        $refreshToken = $this->jwtService->generateRefreshToken([
            'user_id' => $user['id'],
            'email' => $user['email'],
        ]);
        
        return [
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'refresh_token' => $refreshToken,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'employee_id' => $user['employee_id'],
                    'first_name' => $employee['first_name'] ?? '',
                    'last_name' => $employee['last_name'] ?? '',
                ]
            ]
        ];
    }
    
    /**
     * Login with session (web)
     */
    public function loginSession(string $email, string $password): array
    {
        $result = $this->login($email, $password);
        
        if ($result['success']) {
            // Store in session
            $session = session();
            $session->set('user_id', $result['data']['user']['id']);
            $session->set('email', $result['data']['user']['email']);
            $session->set('role_id', $result['data']['user']['role_id']);
            $session->set('employee_id', $result['data']['user']['employee_id']);
            $session->set('first_name', $result['data']['user']['first_name']);
            $session->set('last_name', $result['data']['user']['last_name']);
            $session->set('logged_in', true);
        }
        
        return $result;
    }
    
    /**
     * Logout
     */
    public function logout(): void
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if ($userId) {
            $this->auditLogService->log($userId, 'logout', 'user', $userId);
        }
        
        $session->destroy();
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn(): bool
    {
        $session = session();
        return $session->get('logged_in') === true;
    }
    
    /**
     * Get current user
     */
    public function getCurrentUser(): ?array
    {
        $session = session();
        
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $session->get('user_id'),
            'email' => $session->get('email'),
            'role_id' => $session->get('role_id'),
            'employee_id' => $session->get('employee_id'),
            'first_name' => $session->get('first_name'),
            'last_name' => $session->get('last_name'),
        ];
    }
    
    /**
     * Refresh token
     */
    public function refreshToken(string $refreshToken): array
    {
        $decoded = $this->jwtService->verifyToken($refreshToken);
        
        if (!$decoded || !isset($decoded->type) || $decoded->type !== 'refresh') {
            return [
                'success' => false,
                'message' => 'Invalid refresh token'
            ];
        }
        
        $user = $this->userModel->find($decoded->user_id);
        
        if (!$user || !$user['is_active']) {
            return [
                'success' => false,
                'message' => 'User not found or inactive'
            ];
        }
        
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($user['employee_id']);
        
        $token = $this->jwtService->generateToken([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'employee_id' => $user['employee_id'],
        ]);
        
        return [
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'employee_id' => $user['employee_id'],
                    'first_name' => $employee['first_name'] ?? '',
                    'last_name' => $employee['last_name'] ?? '',
                ]
            ]
        ];
    }
}
