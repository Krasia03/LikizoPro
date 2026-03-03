<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends BaseModel
{
    protected $table = 'roles';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = ['name', 'slug', 'description', 'is_system'];
    
    protected $validationRules = [
        'name' => 'required',
        'slug' => 'required|is_unique[roles.slug,id,{id}]',
    ];
    
    /**
     * Get role with permissions
     */
    public function getWithPermissions(int $id)
    {
        $role = $this->find($id);
        
        if ($role) {
            $permissionModel = new \App\Models\PermissionModel();
            $role['permissions'] = $permissionModel->getByRole($id);
        }
        
        return $role;
    }
    
    /**
     * Get all roles with permissions
     */
    public function getAllWithPermissions()
    {
        $roles = $this->findAll();
        $permissionModel = new \App\Models\PermissionModel();
        
        foreach ($roles as &$role) {
            $role['permissions'] = $permissionModel->getByRole($role['id']);
        }
        
        return $roles;
    }
    
    /**
     * Get system roles
     */
    public function getSystemRoles()
    {
        return $this->where('is_system', 1)->findAll();
    }
}
