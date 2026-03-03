<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends BaseModel
{
    protected $table = 'users';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'employee_id', 'email', 'password', 'role_id', 'is_active', 'last_login'
    ];
    
    protected $hidden = ['password'];
    
    protected $beforeInsert = ['hashPassword'];
    
    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role_id' => 'required|integer',
    ];
    
    /**
     * Hash password before insert
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }
    
    /**
     * Verify password
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Get user by email
     */
    public function getByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Get user with role
     */
    public function getWithRole(int $id)
    {
        return $this->select('users.*, roles.name as role_name, roles.slug as role_slug')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.id', $id)
            ->first();
    }
    
    /**
     * Get user with employee
     */
    public function getWithEmployee(int $id)
    {
        return $this->select('users.*, employees.first_name, employees.last_name, employees.employee_number')
            ->join('employees', 'employees.id = users.employee_id', 'left')
            ->where('users.id', $id)
            ->first();
    }
    
    /**
     * Get all users with role
     */
    public function getAllWithRole()
    {
        return $this->select('users.*, roles.name as role_name, roles.slug as role_slug, employees.first_name, employees.last_name, employees.employee_number')
            ->join('roles', 'roles.id = users.role_id')
            ->join('employees', 'employees.id = users.employee_id', 'left')
            ->orderBy('users.id', 'DESC')
            ->findAll();
    }
    
    /**
     * Update last login
     */
    public function updateLastLogin(int $userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }
}
