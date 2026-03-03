<?php

namespace App\Models;

use CodeIgniter\Model;

class LeavePolicyModel extends BaseModel
{
    protected $table = 'leave_policies';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'leave_type_id', 'name', 'description', 'department_id', 'job_grade_id',
        'location_id', 'employment_type', 'max_days_per_year', 'min_days_per_request',
        'max_days_per_request', 'max_consecutive_days', 'accrual_method', 'accrual_months',
        'pro_rata', 'carry_forward_enabled', 'carry_forward_max_days', 'carry_forward_expiry_months',
        'notice_period_days', 'min_tenure_months', 'require_probation_completion',
        'is_active', 'effective_from', 'effective_to'
    ];
    
    /**
     * Get policy with leave type
     */
    public function getWithLeaveType(int $id)
    {
        return $this->select('leave_policies.*, leave_types.name as leave_type_name, leave_types.code as leave_type_code')
            ->join('leave_types', 'leave_types.id = leave_policies.leave_type_id')
            ->where('leave_policies.id', $id)
            ->first();
    }
    
    /**
     * Get active policies
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    /**
     * Get policies by leave type
     */
    public function getByLeaveType(int $leaveTypeId)
    {
        return $this->where('leave_type_id', $leaveTypeId)
            ->where('is_active', 1)
            ->findAll();
    }
    
    /**
     * Get applicable policy for employee
     */
    public function getApplicablePolicy(int $employeeId, int $leaveTypeId)
    {
        $employeeModel = new \App\Models\EmployeeModel();
        $employee = $employeeModel->getWithDepartment($employeeId);
        
        if (!$employee) return null;
        
        // Find matching policy
        $this->where('leave_type_id', $leaveTypeId);
        $this->where('is_active', 1);
        $this->groupStart();
        $this->where('department_id', null);
        $this->orWhere('department_id', $employee['department_id']);
        $this->groupEnd();
        
        $this->groupStart();
        $this->where('job_grade_id', null);
        $this->orWhere('job_grade_id', $employee['job_grade_id']);
        $this->groupEnd();
        
        $this->groupStart();
        $this->where('location_id', null);
        $this->orWhere('location_id', $employee['location_id']);
        $this->groupEnd();
        
        $this->groupStart();
        $this->where('employment_type', null);
        $this->orWhere('employment_type', $employee['employment_type']);
        $this->groupEnd();
        
        return $this->first();
    }
}
