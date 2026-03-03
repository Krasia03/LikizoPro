<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;

class RolePermissionsSeeder extends Seeder
{
    public function run()
    {
        // Super Admin gets all permissions
        $allPermissions = $this->db->table('permissions')->get()->getResultArray();
        
        $rolePermissions = [];
        foreach ($allPermissions as $perm) {
            $rolePermissions[] = [
                'role_id' => 1, // Super Admin
                'permission_id' => $perm['id'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // HR Manager gets most permissions except system RBAC
        $hrManagerPerms = array_filter($allPermissions, function($p) {
            return !in_array($p['slug'], ['manage_roles', 'manage_permissions']);
        });
        foreach ($hrManagerPerms as $perm) {
            $rolePermissions[] = [
                'role_id' => 2, // HR Manager
                'permission_id' => $perm['id'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // HR Officer permissions
        $hrOfficerPerms = [
            'view_dashboard', 'view_employees', 'create_employee', 'edit_employee',
            'view_leave_types', 'view_policies', 'create_policy', 'edit_policy',
            'view_leave_requests', 'create_leave_request', 'edit_leave_request', 'cancel_leave_request',
            'view_approvals', 'process_approval', 'view_reports', 'create_report', 'export_report',
            'view_audit_logs', 'view_settings', 'view_balances'
        ];
        foreach ($allPermissions as $perm) {
            if (in_array($perm['slug'], $hrOfficerPerms)) {
                $rolePermissions[] = [
                    'role_id' => 3, // HR Officer
                    'permission_id' => $perm['id'],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        // Line Manager permissions
        $managerPerms = [
            'view_dashboard', 'view_employees', 'view_leave_requests', 'create_leave_request',
            'edit_leave_request', 'cancel_leave_request', 'view_approvals', 'process_approval',
            'view_balances'
        ];
        foreach ($allPermissions as $perm) {
            if (in_array($perm['slug'], $managerPerms)) {
                $rolePermissions[] = [
                    'role_id' => 4, // Line Manager
                    'permission_id' => $perm['id'],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        // Employee permissions
        $employeePerms = [
            'view_dashboard', 'view_leave_requests', 'create_leave_request', 
            'edit_leave_request', 'cancel_leave_request', 'view_balances'
        ];
        foreach ($allPermissions as $perm) {
            if (in_array($perm['slug'], $employeePerms)) {
                $rolePermissions[] = [
                    'role_id' => 5, // Employee
                    'permission_id' => $perm['id'],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        // Payroll permissions
        $payrollPerms = [
            'view_dashboard', 'view_employees', 'view_leave_requests', 'view_reports', 'export_report', 'view_balances'
        ];
        foreach ($allPermissions as $perm) {
            if (in_array($perm['slug'], $payrollPerms)) {
                $rolePermissions[] = [
                    'role_id' => 6, // Payroll
                    'permission_id' => $perm['id'],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        // Auditor permissions
        $auditorPerms = [
            'view_dashboard', 'view_employees', 'view_leave_types', 'view_policies',
            'view_leave_requests', 'view_reports', 'view_audit_logs', 'view_settings', 'view_balances'
        ];
        foreach ($allPermissions as $perm) {
            if (in_array($perm['slug'], $auditorPerms)) {
                $rolePermissions[] = [
                    'role_id' => 7, // Auditor
                    'permission_id' => $perm['id'],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        $this->db->table('role_permissions')->insertBatch($rolePermissions);
    }
}
