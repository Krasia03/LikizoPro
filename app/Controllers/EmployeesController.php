<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use App\Models\UserModel;
use App\Models\DepartmentModel;
use App\Models\LocationModel;
use App\Models\JobGradeModel;
use App\Services\RBACService;
use App\Services\AuditLogService;

class EmployeesController extends BaseController
{
    private $rbacService;
    private $auditLogService;
    
    public function __construct()
    {
        $this->rbacService = new RBACService();
        $this->auditLogService = new AuditLogService();
    }
    
    /**
     * List employees
     */
    public function index()
    {
        $user = session();
        $roleId = $user->get('role_id');
        
        if (!$this->rbacService->hasPermission($roleId, 'view_employees')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $employeeModel = new EmployeeModel();
        
        $data = [
            'title' => 'Employees - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'employees' => $employeeModel->getAllWithRelations(),
            'departments' => (new DepartmentModel())->findAll(),
        ];
        
        return view('employees/index', $data);
    }
    
    /**
     * Show create form
     */
    public function create()
    {
        $user = session();
        $roleId = $user->get('role_id');
        
        if (!$this->rbacService->hasPermission($roleId, 'create_employee')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $data = [
            'title' => 'Add Employee - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'departments' => (new DepartmentModel())->findAll(),
            'locations' => (new LocationModel())->findAll(),
            'job_grades' => (new JobGradeModel())->findAll(),
        ];
        
        return view('employees/create', $data);
    }
    
    /**
     * Store employee
     */
    public function store()
    {
        $user = session();
        
        if (!$this->rbacService->hasPermission($user->get('role_id'), 'create_employee')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $employeeModel = new EmployeeModel();
        
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'employee_number' => 'required|is_unique[employees.employee_number]',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|valid_email|is_unique[employees.email]',
            'department_id' => 'required|integer',
            'employment_type' => 'required|in_list[full_time,part_time,contract,intern]',
            'hire_date' => 'required|valid_date',
        ]);
        
        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $employeeId = $employeeModel->insert($this->request->getPost());
        
        if (!$employeeId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create employee');
        }
        
        // Audit log
        $this->auditLogService->logCRUD(
            $user->get('user_id'),
            'create',
            'employee',
            $employeeId,
            null,
            $this->request->getPost()
        );
        
        return redirect()->to('/employees')
            ->with('success', 'Employee created successfully');
    }
    
    /**
     * Show employee details
     */
    public function view($id)
    {
        $user = session();
        $roleId = $user->get('role_id');
        
        if (!$this->rbacService->hasPermission($roleId, 'view_employees')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->getWithDepartment($id);
        
        if (!$employee) {
            return redirect()->to('/employees')->with('error', 'Employee not found');
        }
        
        // Get user account if exists
        $userModel = new UserModel();
        $userAccount = $userModel->where('employee_id', $id)->first();
        
        $data = [
            'title' => $employee['first_name'] . ' ' . $employee['last_name'] . ' - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'employee' => $employee,
            'user_account' => $userAccount,
            'departments' => (new DepartmentModel())->findAll(),
            'locations' => (new LocationModel())->findAll(),
            'job_grades' => (new JobGradeModel())->findAll(),
        ];
        
        return view('employees/view', $data);
    }
    
    /**
     * Show edit form
     */
    public function edit($id)
    {
        $user = session();
        $roleId = $user->get('role_id');
        
        if (!$this->rbacService->hasPermission($roleId, 'edit_employee')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);
        
        if (!$employee) {
            return redirect()->to('/employees')->with('error', 'Employee not found');
        }
        
        $data = [
            'title' => 'Edit Employee - LIKIZOPRO',
            'user' => [
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ],
            'menu' => $this->rbacService->getMenuForRole($roleId),
            'employee' => $employee,
            'departments' => (new DepartmentModel())->findAll(),
            'locations' => (new LocationModel())->findAll(),
            'job_grades' => (new JobGradeModel())->findAll(),
        ];
        
        return view('employees/edit', $data);
    }
    
    /**
     * Update employee
     */
    public function update($id)
    {
        $user = session();
        
        if (!$this->rbacService->hasPermission($user->get('role_id'), 'edit_employee')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);
        
        if (!$employee) {
            return redirect()->to('/employees')->with('error', 'Employee not found');
        }
        
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'employee_number' => "required|is_unique[employees.employee_number,id,{$id}]",
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => "required|valid_email|is_unique[employees.email,id,{$id}]",
            'department_id' => 'required|integer',
            'employment_type' => 'required|in_list[full_time,part_time,contract,intern]',
        ]);
        
        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }
        
        $employeeModel->update($id, $this->request->getPost());
        
        // Audit log
        $this->auditLogService->logCRUD(
            $user->get('user_id'),
            'update',
            'employee',
            $id,
            $employee,
            $this->request->getPost()
        );
        
        return redirect()->to('/employees/view/' . $id)
            ->with('success', 'Employee updated successfully');
    }
    
    /**
     * Delete employee
     */
    public function delete($id)
    {
        $user = session();
        
        if (!$this->rbacService->hasPermission($user->get('role_id'), 'delete_employee')) {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }
        
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);
        
        if (!$employee) {
            return redirect()->to('/employees')->with('error', 'Employee not found');
        }
        
        // Soft delete - just mark as inactive
        $employeeModel->update($id, ['is_active' => 0]);
        
        // Audit log
        $this->auditLogService->logCRUD(
            $user->get('user_id'),
            'delete',
            'employee',
            $id,
            $employee,
            null
        );
        
        return redirect()->to('/employees')
            ->with('success', 'Employee deactivated successfully');
    }
}
