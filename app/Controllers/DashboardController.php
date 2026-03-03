<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use App\Models\LeaveRequestModel;
use App\Models\LeaveTypeModel;
use App\Services\RBACService;

class DashboardController extends BaseController
{
    private $rbacService;
    
    public function __construct()
    {
        $this->rbacService = new RBACService();
    }
    
    /**
     * Main dashboard
     */
    public function index()
    {
        $user = session();
        $roleId = $user->get('role_id');
        $employeeId = $user->get('employee_id');
        
        $data = [
            'title' => 'Dashboard - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
        ];
        
        // Get dashboard statistics
        $employeeModel = new EmployeeModel();
        $leaveRequestModel = new LeaveRequestModel();
        
        // Total employees
        $data['stats']['total_employees'] = $employeeModel->countActive();
        
        // Pending leave requests (for HR/Manager)
        if ($this->rbacService->hasPermission($roleId, 'view_approvals')) {
            $data['stats']['pending_approvals'] = count($leaveRequestModel->getPendingApprovals($employeeId));
        }
        
        // Leave requests by status
        $data['stats']['pending_requests'] = $leaveRequestModel->countByStatus('submitted') 
            + $leaveRequestModel->countByStatus('pending_l1')
            + $leaveRequestModel->countByStatus('pending_l2')
            + $leaveRequestModel->countByStatus('pending_l3');
        
        $data['stats']['approved_today'] = $leaveRequestModel->countByStatus('approved');
        
        // Get recent leave requests
        $data['recent_requests'] = $leaveRequestModel->getAllWithRelations([
            'employee_id' => $employeeId
        ]);
        
        return view('dashboard/index', $data);
    }
}
