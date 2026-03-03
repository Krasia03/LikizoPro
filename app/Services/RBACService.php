<?php

namespace App\Services;

use App\Models\PermissionModel;
use App\Models\RoleModel;

class RBACService
{
    private $permissionModel;
    private $roleModel;
    private $cache = [];
    
    public function __construct()
    {
        $this->permissionModel = new PermissionModel();
        $this->roleModel = new RoleModel();
    }
    
    /**
     * Check if user has permission
     */
    public function hasPermission(int $roleId, string $permissionSlug): bool
    {
        $cacheKey = "role_{$roleId}_perms";
        
        if (!isset($this->cache[$cacheKey])) {
            $permissions = $this->permissionModel->getByRole($roleId);
            $this->cache[$cacheKey] = array_column($permissions, 'slug');
        }
        
        return in_array($permissionSlug, $this->cache[$cacheKey]);
    }
    
    /**
     * Get permissions for role
     */
    public function getPermissions(int $roleId): array
    {
        return $this->permissionModel->getByRole($roleId);
    }
    
    /**
     * Get role permissions as slugs
     */
    public function getPermissionSlugs(int $roleId): array
    {
        $permissions = $this->getPermissions($roleId);
        return array_column($permissions, 'slug');
    }
    
    /**
     * Check if user can access route
     */
    public function canAccess(int $roleId, string $route): bool
    {
        $routePermissions = $this->getRoutePermissions($route);
        
        if (empty($routePermissions)) {
            return true; // No permission required
        }
        
        foreach ($routePermissions as $permission) {
            if ($this->hasPermission($roleId, $permission)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Map routes to required permissions
     */
    private function getRoutePermissions(string $route): array
    {
        $routeMap = [
            'dashboard' => ['view_dashboard'],
            'employees' => ['view_employees', 'create_employee', 'edit_employee', 'delete_employee'],
            'leave_requests' => ['view_leave_requests', 'create_leave_request', 'edit_leave_request', 'cancel_leave_request'],
            'approvals' => ['view_approvals', 'process_approval'],
            'leave_types' => ['view_leave_types', 'create_leave_type', 'edit_leave_type', 'delete_leave_type'],
            'policies' => ['view_policies', 'create_policy', 'edit_policy', 'delete_policy'],
            'reports' => ['view_reports', 'create_report', 'export_report'],
            'audit_logs' => ['view_audit_logs'],
            'settings' => ['view_settings', 'manage_settings'],
            'departments' => ['manage_departments'],
            'locations' => ['manage_locations'],
            'job_grades' => ['manage_job_grades'],
            'work_schedules' => ['manage_work_schedules'],
            'holidays' => ['manage_holidays'],
            'roles' => ['manage_roles'],
            'permissions' => ['manage_permissions'],
            'balances' => ['view_balances', 'manage_balances'],
        ];
        
        foreach ($routeMap as $key => $permissions) {
            if (strpos($route, $key) !== false) {
                return $permissions;
            }
        }
        
        return [];
    }
    
    /**
     * Get menu items for role
     */
    public function getMenuForRole(int $roleId): array
    {
        $menu = [
            [
                'title' => 'Dashboard',
                'icon' => 'bi-speedometer2',
                'route' => 'dashboard',
                'permission' => 'view_dashboard',
            ],
            [
                'title' => 'My Leave',
                'icon' => 'bi-calendar-check',
                'route' => 'leave_requests/my',
                'permission' => 'view_leave_requests',
            ],
            [
                'title' => 'Approvals',
                'icon' => 'bi-check2-circle',
                'route' => 'approvals',
                'permission' => 'view_approvals',
            ],
            [
                'title' => 'Employees',
                'icon' => 'bi-people',
                'route' => 'employees',
                'permission' => 'view_employees',
            ],
            [
                'title' => 'Leave Types',
                'icon' => 'bi-calendar-range',
                'route' => 'leave_types',
                'permission' => 'view_leave_types',
            ],
            [
                'title' => 'Policies',
                'icon' => 'bi-file-earmark-text',
                'route' => 'policies',
                'permission' => 'view_policies',
            ],
            [
                'title' => 'Reports',
                'icon' => 'bi-bar-chart',
                'route' => 'reports',
                'permission' => 'view_reports',
            ],
            [
                'title' => 'Audit Logs',
                'icon' => 'bi-journal-text',
                'route' => 'audit_logs',
                'permission' => 'view_audit_logs',
            ],
            [
                'title' => 'Settings',
                'icon' => 'bi-gear',
                'route' => 'settings',
                'permission' => 'view_settings',
                'children' => [
                    ['title' => 'Departments', 'route' => 'settings/departments', 'permission' => 'manage_departments'],
                    ['title' => 'Locations', 'route' => 'settings/locations', 'permission' => 'manage_locations'],
                    ['title' => 'Job Grades', 'route' => 'settings/job_grades', 'permission' => 'manage_job_grades'],
                    ['title' => 'Work Schedules', 'route' => 'settings/work_schedules', 'permission' => 'manage_work_schedules'],
                    ['title' => 'Holidays', 'route' => 'settings/holidays', 'permission' => 'manage_holidays'],
                ],
            ],
            [
                'title' => 'Access Control',
                'icon' => 'bi-shield-lock',
                'route' => 'rbac',
                'permission' => 'manage_roles',
                'children' => [
                    ['title' => 'Roles', 'route' => 'rbac/roles', 'permission' => 'manage_roles'],
                    ['title' => 'Permissions', 'route' => 'rbac/permissions', 'permission' => 'manage_permissions'],
                ],
            ],
        ];
        
        // Filter menu by permissions
        $filteredMenu = [];
        foreach ($menu as $item) {
            if ($this->hasPermission($roleId, $item['permission'])) {
                if (isset($item['children'])) {
                    $children = [];
                    foreach ($item['children'] as $child) {
                        if ($this->hasPermission($roleId, $child['permission'])) {
                            $children[] = $child;
                        }
                    }
                    if (!empty($children)) {
                        $item['children'] = $children;
                        $filteredMenu[] = $item;
                    }
                } else {
                    $filteredMenu[] = $item;
                }
            }
        }
        
        return $filteredMenu;
    }
}
