<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard', 'module' => 'dashboard', 'description' => 'Access to dashboard'],
            
            // Employees
            ['name' => 'View Employees', 'slug' => 'view_employees', 'module' => 'employees', 'description' => 'View employee list'],
            ['name' => 'Create Employee', 'slug' => 'create_employee', 'module' => 'employees', 'description' => 'Create new employee'],
            ['name' => 'Edit Employee', 'slug' => 'edit_employee', 'module' => 'employees', 'description' => 'Edit employee details'],
            ['name' => 'Delete Employee', 'slug' => 'delete_employee', 'module' => 'employees', 'description' => 'Delete employee'],
            
            // Leave Types
            ['name' => 'View Leave Types', 'slug' => 'view_leave_types', 'module' => 'leave_types', 'description' => 'View leave types'],
            ['name' => 'Create Leave Type', 'slug' => 'create_leave_type', 'module' => 'leave_types', 'description' => 'Create leave type'],
            ['name' => 'Edit Leave Type', 'slug' => 'edit_leave_type', 'module' => 'leave_types', 'description' => 'Edit leave type'],
            ['name' => 'Delete Leave Type', 'slug' => 'delete_leave_type', 'module' => 'leave_types', 'description' => 'Delete leave type'],
            
            // Leave Policies
            ['name' => 'View Policies', 'slug' => 'view_policies', 'module' => 'policies', 'description' => 'View leave policies'],
            ['name' => 'Create Policy', 'slug' => 'create_policy', 'module' => 'policies', 'description' => 'Create leave policy'],
            ['name' => 'Edit Policy', 'slug' => 'edit_policy', 'module' => 'policies', 'description' => 'Edit leave policy'],
            ['name' => 'Delete Policy', 'slug' => 'delete_policy', 'module' => 'policies', 'description' => 'Delete leave policy'],
            
            // Leave Requests
            ['name' => 'View Leave Requests', 'slug' => 'view_leave_requests', 'module' => 'leave_requests', 'description' => 'View leave requests'],
            ['name' => 'Create Leave Request', 'slug' => 'create_leave_request', 'module' => 'leave_requests', 'description' => 'Submit leave request'],
            ['name' => 'Edit Leave Request', 'slug' => 'edit_leave_request', 'module' => 'leave_requests', 'description' => 'Edit own leave request'],
            ['name' => 'Cancel Leave Request', 'slug' => 'cancel_leave_request', 'module' => 'leave_requests', 'description' => 'Cancel own leave request'],
            ['name' => 'Approve Leave Request', 'slug' => 'approve_leave_request', 'module' => 'leave_requests', 'description' => 'Approve leave requests'],
            ['name' => 'Reject Leave Request', 'slug' => 'reject_leave_request', 'module' => 'leave_requests', 'description' => 'Reject leave requests'],
            
            // Approvals
            ['name' => 'View Approvals', 'slug' => 'view_approvals', 'module' => 'approvals', 'description' => 'View approval queue'],
            ['name' => 'Process Approval', 'slug' => 'process_approval', 'module' => 'approvals', 'description' => 'Process approvals'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'view_reports', 'module' => 'reports', 'description' => 'View reports'],
            ['name' => 'Create Report', 'slug' => 'create_report', 'module' => 'reports', 'description' => 'Create custom reports'],
            ['name' => 'Export Report', 'slug' => 'export_report', 'module' => 'reports', 'description' => 'Export reports'],
            
            // Audit Logs
            ['name' => 'View Audit Logs', 'slug' => 'view_audit_logs', 'module' => 'audit', 'description' => 'View audit logs'],
            
            // Settings
            ['name' => 'View Settings', 'slug' => 'view_settings', 'module' => 'settings', 'description' => 'View system settings'],
            ['name' => 'Manage Settings', 'slug' => 'manage_settings', 'module' => 'settings', 'description' => 'Manage system settings'],
            ['name' => 'Manage Departments', 'slug' => 'manage_departments', 'module' => 'settings', 'description' => 'Manage departments'],
            ['name' => 'Manage Locations', 'slug' => 'manage_locations', 'module' => 'settings', 'description' => 'Manage locations'],
            ['name' => 'Manage Job Grades', 'slug' => 'manage_job_grades', 'module' => 'settings', 'description' => 'Manage job grades'],
            ['name' => 'Manage Work Schedules', 'slug' => 'manage_work_schedules', 'module' => 'settings', 'description' => 'Manage work schedules'],
            ['name' => 'Manage Holidays', 'slug' => 'manage_holidays', 'module' => 'settings', 'description' => 'Manage holidays'],
            
            // RBAC
            ['name' => 'Manage Roles', 'slug' => 'manage_roles', 'module' => 'rbac', 'description' => 'Manage roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage_permissions', 'module' => 'rbac', 'description' => 'Manage permissions'],
            
            // Balances
            ['name' => 'View Balances', 'slug' => 'view_balances', 'module' => 'balances', 'description' => 'View leave balances'],
            ['name' => 'Manage Balances', 'slug' => 'manage_balances', 'module' => 'balances', 'description' => 'Adjust leave balances'],
        ];

        $this->db->table('permissions')->insertBatch($permissions);
    }
}
