<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveRequestModel extends BaseModel
{
    protected $table = 'leave_requests';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'employee_id', 'leave_type_id', 'policy_id', 'status',
        'start_date', 'end_date', 'start_half', 'end_half', 'total_days',
        'reason', 'emergency_contact', 'current_approval_level',
        'rejection_reason', 'return_reason', 'submitted_at', 'processed_at', 'processed_by'
    ];
    
    protected $validationRules = [
        'employee_id' => 'required|integer',
        'leave_type_id' => 'required|integer',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date',
        'total_days' => 'required',
    ];
    
    /**
     * Get leave request with employee
     */
    public function getWithEmployee(int $id)
    {
        return $this->select('leave_requests.*, 
            employees.first_name, employees.last_name, employees.employee_number, employees.email as employee_email,
            leave_types.name as leave_type_name, leave_types.color as leave_type_color,
            leave_policies.name as policy_name')
            ->join('employees', 'employees.id = leave_requests.employee_id')
            ->join('leave_types', 'leave_types.id = leave_requests.leave_type_id')
            ->join('leave_policies', 'leave_policies.id = leave_requests.policy_id', 'left')
            ->where('leave_requests.id', $id)
            ->first();
    }
    
    /**
     * Get all leave requests with relations
     */
    public function getAllWithRelations($filters = [])
    {
        $this->select('leave_requests.*, 
            employees.first_name, employees.last_name, employees.employee_number,
            leave_types.name as leave_type_name, leave_types.color as leave_type_color');
        
        $this->join('employees', 'employees.id = leave_requests.employee_id');
        $this->join('leave_types', 'leave_types.id = leave_requests.leave_type_id');
        
        if (!empty($filters['employee_id'])) {
            $this->where('leave_requests.employee_id', $filters['employee_id']);
        }
        
        if (!empty($filters['status'])) {
            $this->where('leave_requests.status', $filters['status']);
        }
        
        if (!empty($filters['leave_type_id'])) {
            $this->where('leave_requests.leave_type_id', $filters['leave_type_id']);
        }
        
        if (!empty($filters['department_id'])) {
            $this->where('employees.department_id', $filters['department_id']);
        }
        
        if (!empty($filters['start_date'])) {
            $this->where('leave_requests.start_date >=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $this->where('leave_requests.end_date <=', $filters['end_date']);
        }
        
        return $this->orderBy('leave_requests.created_at', 'DESC')->findAll();
    }
    
    /**
     * Get employee's leave requests
     */
    public function getByEmployee(int $employeeId, $status = null)
    {
        $this->where('employee_id', $employeeId);
        
        if ($status) {
            $this->where('status', $status);
        }
        
        return $this->orderBy('created_at', 'DESC')->findAll();
    }
    
    /**
     * Get pending approvals for manager
     */
    public function getPendingApprovals(int $managerId)
    {
        return $this->select('leave_requests.*, 
            employees.first_name, employees.last_name, employees.employee_number,
            leave_types.name as leave_type_name, leave_types.color as leave_type_color')
            ->join('employees', 'employees.id = leave_requests.employee_id')
            ->join('leave_types', 'leave_types.id = leave_requests.leave_type_id')
            ->where('employees.supervisor_id', $managerId)
            ->whereIn('leave_requests.status', ['submitted', 'pending_l1', 'pending_l2', 'pending_l3'])
            ->orderBy('leave_requests.submitted_at', 'ASC')
            ->findAll();
    }
    
    /**
     * Get leave request days
     */
    public function getDays(int $requestId)
    {
        $dayModel = new \App\Models\LeaveRequestDayModel();
        return $dayModel->where('leave_request_id', $requestId)->findAll();
    }
    
    /**
     * Get attachments
     */
    public function getAttachments(int $requestId)
    {
        $attachmentModel = new \App\Models\AttachmentModel();
        return $attachmentModel->where('leave_request_id', $requestId)->findAll();
    }
    
    /**
     * Get approval history
     */
    public function getApprovalHistory(int $requestId)
    {
        $approvalModel = new \App\Models\ApprovalActionModel();
        return $approvalModel->getByRequest($requestId);
    }
    
    /**
     * Count by status
     */
    public function countByStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }
    
    /**
     * Get leave requests for date range
     */
    public function getForDateRange($startDate, $endDate, $departmentId = null)
    {
        $this->select('leave_requests.*, employees.first_name, employees.last_name, leave_types.name as leave_type_name, leave_types.color as leave_type_color')
            ->join('employees', 'employees.id = leave_requests.employee_id')
            ->join('leave_types', 'leave_types.id = leave_requests.leave_type_id')
            ->where('leave_requests.status', 'approved')
            ->where("leave_requests.start_date <= ", $endDate)
            ->where("leave_requests.end_date >= ", $startDate);
        
        if ($departmentId) {
            $this->where('employees.department_id', $departmentId);
        }
        
        return $this->findAll();
    }
}
