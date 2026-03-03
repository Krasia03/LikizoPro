<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends BaseModel
{
    protected $table = 'permissions';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = ['name', 'slug', 'description', 'module'];
    
    protected $validationRules = [
        'name' => 'required',
        'slug' => 'required|is_unique[permissions.slug,id,{id}]',
    ];
    
    /**
     * Get permissions by role
     */
    public function getByRole(int $roleId)
    {
        return $this->select('permissions.*')
            ->join('role_permissions', 'role_permissions.permission_id = permissions.id')
            ->where('role_permissions.role_id', $roleId)
            ->findAll();
    }
    
    /**
     * Get permissions by module
     */
    public function getByModule(string $module)
    {
        return $this->where('module', $module)->findAll();
    }
    
    /**
     * Check if role has permission
     */
    public function hasPermission(int $roleId, string $permissionSlug): bool
    {
        $count = $this->join('role_permissions', 'role_permissions.permission_id = permissions.id')
            ->where('role_permissions.role_id', $roleId)
            ->where('permissions.slug', $permissionSlug)
            ->countAllResults();
        
        return $count > 0;
    }
    
    /**
     * Get all permissions grouped by module
     */
    public function getGroupedByModule()
    {
        $permissions = $this->findAll();
        $grouped = [];
        
        foreach ($permissions as $perm) {
            $module = $perm['module'];
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][] = $perm;
        }
        
        return $grouped;
    }
}
