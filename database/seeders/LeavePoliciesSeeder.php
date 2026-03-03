<?php

namespace App\Database\Seeders;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LeavePoliciesSeeder extends Seeder
{
    public function run()
    {
        $policies = [
            // Annual Leave Policy - Default
            [
                'leave_type_id' => 1, // Annual Leave
                'name' => 'Annual Leave - Standard',
                'description' => 'Standard annual leave policy for full-time employees',
                'department_id' => null,
                'job_grade_id' => null,
                'location_id' => null,
                'employment_type' => 'full_time',
                'max_days_per_year' => 18.00,
                'min_days_per_request' => 1.00,
                'max_days_per_request' => 30.00,
                'max_consecutive_days' => 30,
                'accrual_method' => 'monthly',
                'accrual_months' => 12,
                'pro_rata' => 1,
                'carry_forward_enabled' => 1,
                'carry_forward_max_days' => 5.00,
                'carry_forward_expiry_months' => 3,
                'notice_period_days' => 14,
                'min_tenure_months' => 0,
                'require_probation_completion' => 0,
                'is_active' => 1,
                'effective_from' => '2026-01-01',
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            // Sick Leave Policy
            [
                'leave_type_id' => 2, // Sick Leave
                'name' => 'Sick Leave - Standard',
                'description' => 'Standard sick leave policy',
                'department_id' => null,
                'job_grade_id' => null,
                'location_id' => null,
                'employment_type' => 'full_time',
                'max_days_per_year' => 30.00,
                'min_days_per_request' => 0.50,
                'max_days_per_request' => 30.00,
                'max_consecutive_days' => 30,
                'accrual_method' => 'monthly',
                'accrual_months' => 12,
                'pro_rata' => 1,
                'carry_forward_enabled' => 0,
                'carry_forward_max_days' => 0.00,
                'carry_forward_expiry_months' => null,
                'notice_period_days' => 0,
                'min_tenure_months' => 0,
                'require_probation_completion' => 0,
                'is_active' => 1,
                'effective_from' => '2026-01-01',
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            // Maternity Leave Policy
            [
                'leave_type_id' => 3, // Maternity Leave
                'name' => 'Maternity Leave - Standard',
                'description' => 'Standard maternity leave as per labor laws',
                'department_id' => null,
                'job_grade_id' => null,
                'location_id' => null,
                'employment_type' => 'full_time',
                'max_days_per_year' => 84.00,
                'min_days_per_request' => 84.00,
                'max_days_per_request' => 84.00,
                'max_consecutive_days' => 84,
                'accrual_method' => 'upfront',
                'accrual_months' => 12,
                'pro_rata' => 0,
                'carry_forward_enabled' => 0,
                'carry_forward_max_days' => 0.00,
                'carry_forward_expiry_months' => null,
                'notice_period_days' => 30,
                'min_tenure_months' => 0,
                'require_probation_completion' => 0,
                'is_active' => 1,
                'effective_from' => '2026-01-01',
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            // Paternity Leave Policy
            [
                'leave_type_id' => 4, // Paternity Leave
                'name' => 'Paternity Leave - Standard',
                'description' => 'Standard paternity leave for new fathers',
                'department_id' => null,
                'job_grade_id' => null,
                'location_id' => null,
                'employment_type' => 'full_time',
                'max_days_per_year' => 7.00,
                'min_days_per_request' => 7.00,
                'max_days_per_request' => 7.00,
                'max_consecutive_days' => 7,
                'accrual_method' => 'upfront',
                'accrual_months' => 12,
                'pro_rata' => 0,
                'carry_forward_enabled' => 0,
                'carry_forward_max_days' => 0.00,
                'carry_forward_expiry_months' => null,
                'notice_period_days' => 7,
                'min_tenure_months' => 0,
                'require_probation_completion' => 0,
                'is_active' => 1,
                'effective_from' => '2026-01-01',
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            // Compassionate Leave Policy
            [
                'leave_type_id' => 5, // Compassionate Leave
                'name' => 'Compassionate Leave - Standard',
                'description' => 'Leave for bereavement and family emergencies',
                'department_id' => null,
                'job_grade_id' => null,
                'location_id' => null,
                'employment_type' => 'full_time',
                'max_days_per_year' => 10.00,
                'min_days_per_request' => 1.00,
                'max_days_per_request' => 10.00,
                'max_consecutive_days' => 10,
                'accrual_method' => 'upfront',
                'accrual_months' => 12,
                'pro_rata' => 0,
                'carry_forward_enabled' => 0,
                'carry_forward_max_days' => 0.00,
                'carry_forward_expiry_months' => null,
                'notice_period_days' => 0,
                'min_tenure_months' => 0,
                'require_probation_completion' => 0,
                'is_active' => 1,
                'effective_from' => '2026-01-01',
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
            // Study Leave Policy
            [
                'leave_type_id' => 6, // Study Leave
                'name' => 'Study Leave - Standard',
                'description' => 'Leave for professional development and studies',
                'department_id' => null,
                'job_grade_id' => null,
                'location_id' => null,
                'employment_type' => 'full_time',
                'max_days_per_year' => 10.00,
                'min_days_per_request' => 0.50,
                'max_days_per_request' => 10.00,
                'max_consecutive_days' => 10,
                'accrual_method' => 'yearly',
                'accrual_months' => 12,
                'pro_rata' => 1,
                'carry_forward_enabled' => 0,
                'carry_forward_max_days' => 0.00,
                'carry_forward_expiry_months' => null,
                'notice_period_days' => 30,
                'min_tenure_months' => 6,
                'require_probation_completion' => 1,
                'is_active' => 1,
                'effective_from' => '2026-01-01',
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
            ],
        ];

        $this->db->table('leave_policies')->insertBatch($policies);
    }
}
