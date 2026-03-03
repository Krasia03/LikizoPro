<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovalActionModel extends BaseModel
{
    protected $table = 'approval_actions';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'leave_request_id', 'approver_id', 'approval_level', 'action', 'comments', 'delegated_from'
    ];
    
    /**
     * Get approvals by request
     */
    public function getByRequest(int $requestId)
    {
        return $this->select('approval_actions.*, employees.first_name, employees.last_name')
            ->join('employees', 'employees.id = approval_actions.approver_id')
            ->where('leave_request_id', $requestId)
            ->orderBy('approval_actions.created_at', 'ASC')
            ->findAll();
    }
}
