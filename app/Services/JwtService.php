<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\Config\Services;

class JwtService
{
    private $key;
    private $algorithm;
    private $expiry;
    
    public function __construct(string $key, string $algorithm = 'HS256', int $expiry = 86400)
    {
        $this->key = $key;
        $this->algorithm = $algorithm;
        $this->expiry = $expiry;
    }
    
    /**
     * Generate JWT token
     */
    public function generateToken(array $payload): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->expiry;
        
        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expire,
        ]);
        
        return JWT::encode($tokenPayload, $this->key, $this->algorithm);
    }
    
    /**
     * Generate refresh token
     */
    public function generateRefreshToken(array $payload): string
    {
        $issuedAt = time();
        $expire = $issuedAt + (86400 * 7); // 7 days
        
        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expire,
            'type' => 'refresh',
        ]);
        
        return JWT::encode($tokenPayload, $this->key, $this->algorithm);
    }
    
    /**
     * Verify and decode JWT token
     */
    public function verifyToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->key, $this->algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Get current user ID from token
     */
    public function getUserIdFromToken(string $token): ?int
    {
        $decoded = $this->verifyToken($token);
        return $decoded ? ($decoded->user_id ?? null) : null;
    }
    
    /**
     * Get expiry timestamp
     */
    public function getExpiry(): int
    {
        return $this->expiry;
    }
}
