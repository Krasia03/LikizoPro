<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LeaveRequestModel;
use App\Models\ApprovalActionModel;
use App\Services\RBACService;
use App\Services\AuditLogService;

class ApprovalsController extends BaseController
{
    private $rbacService;
    private $auditLogService;
    
    public function __construct()
    {
        $this->rbacService = new RBACService();
        $this->auditLogService = new AuditLogService();
    }
    
    /**
     * List pending approvals
     */
    public function index()
    {
        $user = session();
        $roleId = $user->get('role_id');
        $employeeId = $user->get('employee_id');
        
        // Check permission
        if (!$this->rbacService->hasPermission($roleId, 'view_approvals')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $leaveRequestModel = new LeaveRequestModel();
        
        $data = [
            'title' => 'Pending Approvals - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'pending_approvals' => $leaveRequestModel->getPendingApprovals($employeeId),
        ];
        
        return view('approvals/index', $data);
    }
    
    /**
     * View approval details
     */
    public function view($id)
    {
        $user = session();
        $roleId = $user->get('role_id');
        
        if (!$this->rbacService->hasPermission($roleId, 'view_approvals')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->getWithEmployee($id);
        
        if (!$request) {
            return redirect()->to('/approvals')->with('error', 'Request not found');
        }
        
        $data = [
            'title' => 'Approval Details - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'request' => $request,
            'days' => $leaveRequestModel->getDays($id),
            'attachments' => $leaveRequestModel->getAttachments($id),
            'approval_history' => $leaveRequestModel->getApprovalHistory($id),
        ];
        
        return view('approvals/view', $data);
    }
    
    /**
     * Approve request
     */
    public function approve($id)
    {
        $user = session();
        $employeeId = $user->get('employee_id');
        
        if (!$this->rbacService->hasPermission($user->get('role_id'), 'process_approval')) {
            return redirect()->back()->with('error', 'Access denied');
        }
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->find($id);
        
        if (!$request) {
            return redirect()->back()->with('error', 'Request not found');
        }
        
        if (!in_array($request['status'], ['submitted', 'pending_l1', 'pending_l2', 'pending_l3'])) {
            return redirect()->back()->with('error', 'This request cannot be approved');
        }
        
        $comments = $this->request->getPost('comments');
        
        // Determine next approval level
        $currentLevel = $request['current_approval_level'];
        $nextStatus = '';
        
        if ($currentLevel == 0 || $currentLevel == 1) {
            $nextStatus = 'pending_l2';
        } elseif ($currentLevel == 2) {
            $nextStatus = 'pending_l3';
        } else {
            $nextStatus = 'approved';
        }
        
        // Update request
        $leaveRequestModel->update($id, [
            'status' => $nextStatus,
            'current_approval_level' => $currentLevel + 1,
            'processed_at' => date('Y-m-d H:i:s'),
            'processed_by' => $employeeId,
        ]);
        
        // Log approval action
        $approvalModel = new ApprovalActionModel();
        $approvalModel->insert([
            'leave_request_id' => $id,
            'approver_id' => $employeeId,
            'approval_level' => $currentLevel + 1,
            'action' => 'approved',
            'comments' => $comments,
        ]);
        
        // Audit log
        $this->auditLogService->logLeaveRequest(
            $user->get('user_id'),
            $id,
            'approved_leave_request',
            ['status' => $nextStatus, 'level' => $currentLevel + 1]
        );
        
        return redirect()->to('/approvals')
            ->with('success', 'Leave request approved successfully');
    }
    
    /**
     * Reject request
     */
    public function reject($id)
    {
        $user = session();
        $employeeId = $user->get('employee_id');
        
        if (!$this->rbacService->hasPermission($user->get('role_id'), 'process_approval')) {
            return redirect()->back()->with('error', 'Access denied');
        }
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->find($id);
        
        if (!$request) {
            return redirect()->back()->with('error', 'Request not found');
        }
        
        if (!in_array($request['status'], ['submitted', 'pending_l1', 'pending_l2', 'pending_l3'])) {
            return redirect()->back()->with('error', 'This request cannot be rejected');
        }
        
        $comments = $this->request->getPost('comments');
        
        if (empty($comments)) {
            return redirect()->back()->with('error', 'Rejection reason is required');
        }
        
        // Update request
        $leaveRequestModel->update($id, [
            'status' => 'rejected',
            'rejection_reason' => $comments,
            'processed_at' => date('Y-m-d H:i:s'),
            'processed_by' => $employeeId,
        ]);
        
        // Log rejection action
        $approvalModel = new ApprovalActionModel();
        $approvalModel->insert([
            'leave_request_id' => $id,
            'approver_id' => $employeeId,
            'approval_level' => $request['current_approval_level'] + 1,
            'action' => 'rejected',
            'comments' => $comments,
        ]);
        
        // Reverse pending transaction
        $transactionModel = new \App\Models\LeaveTransactionModel();
        $transactionModel->reverseOnReject($id);
        
        // Audit log
        $this->auditLogService->logLeaveRequest(
            $user->get('user_id'),
            $id,
            'rejected_leave_request',
            ['reason' => $comments]
        );
        
        return redirect()->to('/approvals')
            ->with('success', 'Leave request rejected');
    }
    
    /**
     * Return request for edit
     */
    public function return($id)
    {
        $user = session();
        $employeeId = $user->get('employee_id');
        
        if (!$this->rbacService->hasPermission($user->get('role_id'), 'process_approval')) {
            return redirect()->back()->with('error', 'Access denied');
        }
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->find($id);
        
        if (!$request) {
            return redirect()->back()->with('error', 'Request not found');
        }
        
        $comments = $this->request->getPost('comments');
        
        if (empty($comments)) {
            return redirect()->back()->with('error', 'Return reason is required');
        }
        
        // Update request
        $leaveRequestModel->update($id, [
            'status' => 'returned',
            'return_reason' => $comments,
        ]);
        
        // Log return action
        $approvalModel = new ApprovalActionModel();
        $approvalModel->insert([
            'leave_request_id' => $id,
            'approver_id' => $employeeId,
            'approval_level' => $request['current_approval_level'] + 1,
            'action' => 'returned',
            'comments' => $comments,
        ]);
        
        // Audit log
        $this->auditLogService->logLeaveRequest(
            $user->get('user_id'),
            $id,
            'returned_leave_request',
            ['reason' => $comments]
        );
        
        return redirect()->to('/approvals')
            ->with('success', 'Leave request returned for editing');
    }
}
