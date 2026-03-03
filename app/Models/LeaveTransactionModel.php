<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveTransactionModel extends BaseModel
{
    protected $table = 'leave_transactions';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'employee_id', 'leave_type_id', 'transaction_type', 'leave_request_id',
        'days', 'balance_before', 'balance_after', 'description', 'reference_number',
        'fiscal_year', 'processed_by'
    ];
    
    /**
     * Get transactions by employee and leave type
     */
    public function getByEmployeeAndType(int $employeeId, int $leaveTypeId, int $year = null)
    {
        $this->where('employee_id', $employeeId);
        $this->where('leave_type_id', $leaveTypeId);
        
        if ($year) {
            $this->where('fiscal_year', $year);
        }
        
        return $this->orderBy('created_at', 'DESC')->findAll();
    }
    
    /**
     * Get balance for employee and leave type
     */
    public function getBalance(int $employeeId, int $leaveTypeId, int $year = null)
    {
        $this->select('SUM(CASE 
            WHEN transaction_type IN ("ACCRUAL", "CARRY_FORWARD", "REJECTION_REVERSAL", "CANCELLATION_REVERSAL", "ADJUSTMENT", "INITIAL") THEN days
            WHEN transaction_type IN ("SUBMISSION_PENDING", "APPROVAL_USED") THEN -days
            ELSE 0
        END) as balance');
        
        $this->where('employee_id', $employeeId);
        $this->where('leave_type_id', $leaveTypeId);
        
        if ($year) {
            $this->where('fiscal_year', $year);
        }
        
        $result = $this->first();
        return $result['balance'] ?? 0;
    }
    
    /**
     * Get all transactions for employee
     */
    public function getByEmployee(int $employeeId, int $year = null)
    {
        $this->where('employee_id', $employeeId);
        
        if ($year) {
            $this->where('fiscal_year', $year);
        }
        
        return $this->orderBy('created_at', 'DESC')->findAll();
    }
    
    /**
     * Create accrual transaction
     */
    public function createAccrual(int $employeeId, int $leaveTypeId, float $days, int $year, int $processedBy = null, string $description = '')
    {
        $balanceBefore = $this->getBalance($employeeId, $leaveTypeId, $year);
        $balanceAfter = $balanceBefore + $days;
        
        return $this->insert([
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'transaction_type' => 'ACCRUAL',
            'days' => $days,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description ?: 'Monthly accrual',
            'fiscal_year' => $year,
            'processed_by' => $processedBy,
        ]);
    }
    
    /**
     * Create pending submission transaction
     */
    public function createPending(int $employeeId, int $leaveTypeId, float $days, int $leaveRequestId, int $year)
    {
        $balanceBefore = $this->getBalance($employeeId, $leaveTypeId, $year);
        $balanceAfter = $balanceBefore - $days;
        
        return $this->insert([
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'transaction_type' => 'SUBMISSION_PENDING',
            'leave_request_id' => $leaveRequestId,
            'days' => $days,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => 'Leave request submitted',
            'fiscal_year' => $year,
        ]);
    }
    
    /**
     * Reverse pending transaction on rejection
     */
    public function reverseOnReject(int $leaveRequestId)
    {
        $leaveRequestModel = new \App\Models\LeaveRequestModel();
        $request = $leaveRequestModel->find($leaveRequestId);
        
        if (!$request) return false;
        
        $balanceBefore = $this->getBalance($request['employee_id'], $request['leave_type_id'], date('Y'));
        $balanceAfter = $balanceBefore + $request['total_days'];
        
        return $this->insert([
            'employee_id' => $request['employee_id'],
            'leave_type_id' => $request['leave_type_id'],
            'transaction_type' => 'REJECTION_REVERSAL',
            'leave_request_id' => $leaveRequestId,
            'days' => $request['total_days'],
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => 'Leave request rejected - balance restored',
            'fiscal_year' => date('Y'),
        ]);
    }
    
    /**
     * Reverse pending transaction on cancellation
     */
    public function reverseOnCancel(int $leaveRequestId)
    {
        $leaveRequestModel = new \App\Models\LeaveRequestModel();
        $request = $leaveRequestModel->find($leaveRequestId);
        
        if (!$request) return false;
        
        $balanceBefore = $this->getBalance($request['employee_id'], $request['leave_type_id'], date('Y'));
        $balanceAfter = $balanceBefore + $request['total_days'];
        
        return $this->insert([
            'employee_id' => $request['employee_id'],
            'leave_type_id' => $request['leave_type_id'],
            'transaction_type' => 'CANCELLATION_REVERSAL',
            'leave_request_id' => $leaveRequestId,
            'days' => $request['total_days'],
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => 'Leave request cancelled - balance restored',
            'fiscal_year' => date('Y'),
        ]);
    }
}
