<?php

namespace Config;

class Auth extends \CodeIgniter\Config\BaseConfig
{
    // Session name
    public $sessionName = 'likizopro_session';
    
    // JWT Configuration
    public $jwtKey = 'likizopro_jwt_secret_key_2026_change_in_production';
    public $jwtAlgorithm = 'HS256';
    public $jwtExpiry = 86400; // 24 hours in seconds
    
    // JWT Refresh token
    public $refreshTokenExpiry = 604800; // 7 days
    
    // Authentication
    public $requireActivation = false;
    public $allowRegistration = false;
    
    // Password hashing
    public $hashAlgorithm = PASSWORD_BCRYPT;
    public $hashCost = 10;
    
    // Default role for new users
    public $defaultRole = 'employee';
}
