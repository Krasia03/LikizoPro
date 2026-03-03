<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LeaveRequestModel;
use App\Models\LeaveTypeModel;
use App\Models\EmployeeModel;
use App\Models\LeavePolicyModel;
use App\Models\LeaveTransactionModel;
use App\Models\AttachmentModel;
use App\Services\RBACService;
use App\Services\AuditLogService;

class LeaveRequestsController extends BaseController
{
    private $rbacService;
    private $auditLogService;
    
    public function __construct()
    {
        $this->rbacService = new RBACService();
        $this->auditLogService = new AuditLogService();
    }
    
    /**
     * List leave requests
     */
    public function index()
    {
        $user = session();
        $roleId = $user->get('role_id');
        $employeeId = $user->get('employee_id');
        
        $leaveRequestModel = new LeaveRequestModel();
        
        // Get filters
        $status = $this->request->getGet('status');
        $leaveTypeId = $this->request->getGet('leave_type_id');
        
        $filters = [];
        
        // Employees can only see their own requests
        if (!$this->rbacService->hasPermission($roleId, 'view_employees')) {
            $filters['employee_id'] = $employeeId;
        } else {
            // HR can see all, but filter by employee if specified
            $filterEmployee = $this->request->getGet('employee_id');
            if ($filterEmployee) {
                $filters['employee_id'] = $filterEmployee;
            }
        }
        
        if ($status) {
            $filters['status'] = $status;
        }
        
        if ($leaveTypeId) {
            $filters['leave_type_id'] = $leaveTypeId;
        }
        
        $data = [
            'title' => 'Leave Requests - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'leave_requests' => $leaveRequestModel->getAllWithRelations($filters),
            'leave_types' => (new LeaveTypeModel())->getActive(),
            'filters' => $filters,
        ];
        
        return view('leave_requests/index', $data);
    }
    
    /**
     * My leave requests
     */
    public function my()
    {
        $user = session();
        $roleId = $user->get('role_id');
        $employeeId = $user->get('employee_id');
        
        $leaveRequestModel = new LeaveRequestModel();
        
        $data = [
            'title' => 'My Leave - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'leave_requests' => $leaveRequestModel->getByEmployee($employeeId),
            'leave_types' => (new LeaveTypeModel())->getActive(),
        ];
        
        return view('leave_requests/my', $data);
    }
    
    /**
     * Show create form
     */
    public function create()
    {
        $user = session();
        $roleId = $user->get('role_id');
        
        $data = [
            'title' => 'Apply Leave - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'leave_types' => (new LeaveTypeModel())->getActive(),
        ];
        
        return view('leave_requests/create', $data);
    }
    
    /**
     * Store leave request
     */
    public function store()
    {
        $user = session();
        $employeeId = $user->get('employee_id');
        
        $leaveTypeId = $this->request->getPost('leave_type_id');
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        $startHalf = $this->request->getPost('start_half') ?? 'full';
        $endHalf = $this->request->getPost('end_half') ?? 'full';
        $reason = $this->request->getPost('reason');
        $emergencyContact = $this->request->getPost('emergency_contact');
        
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'leave_type_id' => 'required|integer',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
            'reason' => 'required|min_length[10]',
        ]);
        
        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        // Validate dates
        if (strtotime($endDate) < strtotime($startDate)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'End date must be after start date');
        }
        
        // Calculate total days
        $totalDays = $this->calculateDays($startDate, $endDate, $startHalf, $endHalf);
        
        // Get applicable policy
        $policyModel = new LeavePolicyModel();
        $policy = $policyModel->getApplicablePolicy($employeeId, $leaveTypeId);
        
        if (!$policy) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No applicable policy found for this leave type');
        }
        
        // Check if attachment required
        $leaveTypeModel = new LeaveTypeModel();
        $leaveType = $leaveTypeModel->find($leaveTypeId);
        
        if ($leaveType['requires_attachment'] && empty($_FILES['attachment']['name'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Attachment is required for this leave type');
        }
        
        // Check balance for paid leave
        if ($leaveType['is_paid']) {
            $transactionModel = new LeaveTransactionModel();
            $balance = $transactionModel->getBalance($employeeId, $leaveTypeId, date('Y'));
            
            if ($balance < $totalDays) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient leave balance. Available: ' . $balance . ' days');
            }
        }
        
        // Create leave request
        $leaveRequestModel = new LeaveRequestModel();
        
        $requestId = $leaveRequestModel->insert([
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'policy_id' => $policy['id'] ?? null,
            'status' => 'draft',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_half' => $startHalf,
            'end_half' => $endHalf,
            'total_days' => $totalDays,
            'reason' => $reason,
            'emergency_contact' => $emergencyContact,
        ]);
        
        if (!$requestId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create leave request');
        }
        
        // Handle attachment upload
        if (!empty($_FILES['attachment']['name'])) {
            $attachmentModel = new AttachmentModel();
            $this->handleAttachmentUpload($requestId, $employeeId);
        }
        
        // Audit log
        $this->auditLogService->logLeaveRequest(
            $user->get('user_id'),
            $requestId,
            'created_leave_request',
            ['leave_type_id' => $leaveTypeId, 'total_days' => $totalDays]
        );
        
        return redirect()->to('/leave_requests/my')
            ->with('success', 'Leave request created successfully');
    }
    
    /**
     * Submit leave request
     */
    public function submit($id)
    {
        $user = session();
        $employeeId = $user->get('employee_id');
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->find($id);
        
        if (!$request || $request['employee_id'] != $employeeId) {
            return redirect()->back()->with('error', 'Leave request not found');
        }
        
        if ($request['status'] !== 'draft' && $request['status'] !== 'returned') {
            return redirect()->back()->with('error', 'This request cannot be submitted');
        }
        
        // Update status
        $leaveRequestModel->update($id, [
            'status' => 'submitted',
            'submitted_at' => date('Y-m-d H:i:s'),
        ]);
        
        // Create pending transaction
        $transactionModel = new LeaveTransactionModel();
        $leaveTypeModel = new LeaveTypeModel();
        $leaveType = $leaveTypeModel->find($request['leave_type_id']);
        
        if ($leaveType['is_paid']) {
            $transactionModel->createPending(
                $employeeId,
                $request['leave_type_id'],
                $request['total_days'],
                $id,
                date('Y')
            );
        }
        
        // Audit log
        $this->auditLogService->logLeaveRequest(
            $user->get('user_id'),
            $id,
            'submitted_leave_request',
            ['total_days' => $request['total_days']]
        );
        
        return redirect()->back()->with('success', 'Leave request submitted successfully');
    }
    
    /**
     * Cancel leave request
     */
    public function cancel($id)
    {
        $user = session();
        $employeeId = $user->get('employee_id');
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->find($id);
        
        if (!$request || $request['employee_id'] != $employeeId) {
            return redirect()->back()->with('error', 'Leave request not found');
        }
        
        if (!in_array($request['status'], ['draft', 'submitted', 'pending_l1', 'pending_l2', 'pending_l3'])) {
            return redirect()->back()->with('error', 'This request cannot be cancelled');
        }
        
        // Update status
        $leaveRequestModel->update($id, [
            'status' => 'cancelled',
        ]);
        
        // Reverse pending transaction
        $transactionModel = new LeaveTransactionModel();
        $transactionModel->reverseOnCancel($id);
        
        // Audit log
        $this->auditLogService->logLeaveRequest(
            $user->get('user_id'),
            $id,
            'cancelled_leave_request',
            []
        );
        
        return redirect()->back()->with('success', 'Leave request cancelled');
    }
    
    /**
     * View leave request
     */
    public function view($id)
    {
        $user = session();
        $roleId = $user->get('role_id');
        
        $leaveRequestModel = new LeaveRequestModel();
        $request = $leaveRequestModel->getWithEmployee($id);
        
        if (!$request) {
            return redirect()->to('/leave_requests')->with('error', 'Leave request not found');
        }
        
        // Check access
        $employeeId = $user->get('employee_id');
        if (!$this->rbacService->hasPermission($roleId, 'view_employees') && $request['employee_id'] != $employeeId) {
            return redirect()->to('/leave_requests')->with('error', 'Access denied');
        }
        
        $data = [
            'title' => 'Leave Request #' . $id . ' - LIKIZOPRO',
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
        
        return view('leave_requests/view', $data);
    }
    
    /**
     * Calculate working days
     */
    private function calculateDays($startDate, $endDate, $startHalf, $endHalf): float
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        
        $days = 0;
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $end->modify('+1 day'));
        
        foreach ($period as $date) {
            $dayOfWeek = $date->format('N');
            
            // Skip weekends (6 = Saturday, 7 = Sunday)
            if ($dayOfWeek >= 6) {
                continue;
            }
            
            // Check if it's a holiday (simplified)
            // In production, check against holidays table
            
            if ($date == $start && $startHalf !== 'full') {
                $days += 0.5;
            } elseif ($date == $end && $endHalf !== 'full') {
                $days += 0.5;
            } else {
                $days += 1;
            }
        }
        
        return $days;
    }
    
    /**
     * Handle attachment upload
     */
    private function handleAttachmentUpload(int $requestId, int $employeeId): bool
    {
        $file = $this->request->getFile('attachment');
        
        if (!$file->isValid()) {
            return false;
        }
        
        $newName = $file->getRandomName();
        $uploadPath = WRITEPATH . 'uploads/leave_requests/';
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $file->move($uploadPath, $newName);
        
        $attachmentModel = new AttachmentModel();
        $attachmentModel->insert([
            'leave_request_id' => $requestId,
            'employee_id' => $employeeId,
            'file_name' => $newName,
            'original_name' => $file->getClientName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_path' => 'uploads/leave_requests/' . $newName,
            'uploaded_by' => $employeeId,
        ]);
        
        return true;
    }
}
