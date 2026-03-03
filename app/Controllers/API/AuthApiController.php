<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\EmployeeModel;
use App\Models\LeaveRequestModel;
use App\Models\LeaveTypeModel;
use App\Models\LeaveTransactionModel;
use App\Services\JwtService;
use Config\Auth;

class AuthApiController extends BaseController
{
    private $jwtService;
    
    public function __construct()
    {
        $config = config('Auth');
        $this->jwtService = new JwtService($config->jwtKey, $config->jwtAlgorithm, $config->jwtExpiry);
    }
    
    /**
     * POST /api/auth/login
     */
    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        if (!$email || !$password) {
            return $this->errorResponse('Email and password are required', null, 400);
        }
        
        $userModel = new UserModel();
        $user = $userModel->getByEmail($email);
        
        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            return $this->errorResponse('Invalid credentials', null, 401);
        }
        
        if (!$user['is_active']) {
            return $this->errorResponse('Account is deactivated', null, 401);
        }
        
        // Get employee data
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($user['employee_id']);
        
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
        
        return $this->successResponse('Login successful', [
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
        ]);
    }
    
    /**
     * POST /api/auth/refresh
     */
    public function refresh()
    {
        $refreshToken = $this->request->getPost('refresh_token');
        
        if (!$refreshToken) {
            return $this->errorResponse('Refresh token is required', null, 400);
        }
        
        $decoded = $this->jwtService->verifyToken($refreshToken);
        
        if (!$decoded || !isset($decoded->type) || $decoded->type !== 'refresh') {
            return $this->errorResponse('Invalid refresh token', null, 401);
        }
        
        $userModel = new UserModel();
        $user = $userModel->find($decoded->user_id);
        
        if (!$user || !$user['is_active']) {
            return $this->errorResponse('User not found or inactive', null, 401);
        }
        
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($user['employee_id']);
        
        $token = $this->jwtService->generateToken([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'employee_id' => $user['employee_id'],
        ]);
        
        return $this->successResponse('Token refreshed', [
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'role_id' => $user['role_id'],
                'employee_id' => $user['employee_id'],
                'first_name' => $employee['first_name'] ?? '',
                'last_name' => $employee['last_name'] ?? '',
            ]
        ]);
    }
    
    /**
     * POST /api/auth/logout
     */
    public function logout()
    {
        // For JWT, logout is handled client-side by removing the token
        return $this->successResponse('Logged out successfully');
    }
    
    /**
     * GET /api/auth/me
     */
    public function me()
    {
        $userData = $this->request->userData;
        
        if (!$userData) {
            return $this->errorResponse('Unauthorized', null, 401);
        }
        
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($userData['employee_id']);
        
        return $this->successResponse('User data', [
            'id' => $userData['user_id'],
            'email' => $userData['email'],
            'role_id' => $userData['role_id'],
            'employee_id' => $userData['employee_id'],
            'first_name' => $employee['first_name'] ?? '',
            'last_name' => $employee['last_name'] ?? '',
        ]);
    }
}
