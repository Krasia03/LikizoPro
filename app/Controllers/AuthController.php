<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\AuthService;

class AuthController extends BaseController
{
    private $authService;
    
    public function __construct()
    {
        $this->authService = new AuthService();
    }
    
    /**
     * Show login page
     */
    public function login()
    {
        if ($this->authService->isLoggedIn()) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/login', [
            'title' => 'Login - LIKIZOPRO'
        ]);
    }
    
    /**
     * Process login
     */
    public function doLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ]);
        
        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $result = $this->authService->loginSession($email, $password);
        
        if ($result['success']) {
            return redirect()->to('/dashboard')->with('success', 'Welcome back!');
        }
        
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        $this->authService->logout();
        
        return redirect()->to('/auth/login')
            ->with('success', 'You have been logged out successfully');
    }
}
