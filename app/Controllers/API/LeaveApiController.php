<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\LeaveRequestModel;
use App\Models\LeaveTypeModel;
use App\Models\LeaveTransactionModel;

class LeaveApiController extends BaseController
{
    /**
     * GET /api/leave-requests
     */
    public function index()
    {
        $userData = $this->request->userData;
        
        $leaveRequestModel = new LeaveRequestModel();
        
        $mine = $this->request->getGet('mine');
        $status = $this->request->getGet('status');
        
        $filters = [];
        
        // Regular employees can only see their own requests
        if ($mine === 'true' || in_array($userData['role_id'], [5])) { // 5 = Employee
            $filters['employee_id'] = $userData['employee_id'];
        }
        
        if ($status) {
            $filters['status'] = $status;
        }
        
        $requests = $leaveRequestModel->getAllWithRelations($filters);
        
        return $this->successResponse('Leave requests retrieved', $requests);
    }
    
    /**
     * POST /api/leave-requests
     */
    public function create()
    {
        $userData = $this->request->userData;
        
        $leaveTypeId = $this->request->getPost('leave_type_id');
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        $reason = $this->request->getPost('reason');
        
        if (!$leaveTypeId || !$startDate || !$endDate || !$reason) {
            return $this->errorResponse('Missing required fields', null, 400);
        }
        
        // Calculate days (simplified)
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $totalDays = ($end->diff($start)->days) + 1;
        
        $leaveRequestModel = new LeaveRequestModel();
        
        $requestId = $leaveRequestModel->insert([
            'employee_id' => $userData['employee_id'],
            'leave_type_id' => $leaveTypeId,
            'status' => 'draft',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $reason,
        ]);
        
        return $this->successResponse('Leave request created', ['id' => $requestId], 201);
    }
    
    /**
     * GET /api/leave-requests/{id}
     */
    public function show($id)
    {
        $userData = $this->request->userData;
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->getWithEmployee($id);
        
        if (!$request) {
            return $this->errorResponse('Leave request not found', null, 404);
        }
        
        // Check access
        if ($request['employee_id'] != $userData['employee_id'] && !in_array($userData['role_id'], [1, 2, 3, 4])) {
            return $this->errorResponse('Access denied', null, 403);
        }
        
        return $this->successResponse('Leave request details', $request);
    }
    
    /**
     * POST /api/leave-requests/{id}/submit
     */
    public function submit($id)
    {
        $userData = $this->request->userData;
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->find($id);
        
        if (!$request || $request['employee_id'] != $userData['employee_id']) {
            return $this->errorResponse('Leave request not found', null, 404);
        }
        
        if (!in_array($request['status'], ['draft', 'returned'])) {
            return $this->errorResponse('This request cannot be submitted', null, 400);
        }
        
        $leaveRequestModel->update($id, [
            'status' => 'submitted',
            'submitted_at' => date('Y-m-d H:i:s'),
        ]);
        
        return $this->successResponse('Leave request submitted');
    }
    
    /**
     * POST /api/leave-requests/{id}/cancel
     */
    public function cancel($id)
    {
        $userData = $this->request->userData;
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->find($id);
        
        if (!$request || $request['employee_id'] != $userData['employee_id']) {
            return $this->errorResponse('Leave request not found', null, 404);
        }
        
        if (!in_array($request['status'], ['draft', 'submitted', 'pending_l1', 'pending_l2', 'pending_l3'])) {
            return $this->errorResponse('This request cannot be cancelled', null, 400);
        }
        
        $leaveRequestModel->update($id, ['status' => 'cancelled']);
        
        return $this->successResponse('Leave request cancelled');
    }
    
    /**
     * GET /api/leave-balances
     */
    public function balances()
    {
        $userData = $this->request->userData;
        
        $leaveTypeModel = new LeaveTypeModel();
        $transactionModel = new LeaveTransactionModel();
        
        $leaveTypes = $leaveTypeModel->getActive();
        $balances = [];
        
        foreach ($leaveTypes as $type) {
            $balance = $transactionModel->getBalance(
                $userData['employee_id'],
                $type['id'],
                date('Y')
            );
            
            $balances[] = [
                'leave_type_id' => $type['id'],
                'leave_type_name' => $type['name'],
                'leave_type_color' => $type['color'],
                'balance' => $balance,
            ];
        }
        
        return $this->successResponse('Leave balances', $balances);
    }
}
