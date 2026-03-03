<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends BaseModel
{
    protected $table = 'employees';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'employee_number', 'first_name', 'last_name', 'email', 'phone', 
        'department_id', 'location_id', 'job_grade_id', 'supervisor_id', 
        'work_schedule_id', 'employment_type', 'employment_status', 
        'hire_date', 'probation_end_date', 'contract_end_date', 'photo',
        'gender', 'date_of_birth', 'address', 'is_active'
    ];
    
    protected $validationRules = [
        'employee_number' => 'required|is_unique[employees.employee_number,id,{id}]',
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|valid_email',
        'department_id' => 'permit_empty|integer',
        'employment_type' => 'required|in_list[full_time,part_time,contract,intern]',
    ];
    
    /**
     * Get employee with department
     */
    public function getWithDepartment(int $id)
    {
        return $this->select('employees.*, departments.name as department_name, departments.code as department_code')
            ->join('departments', 'departments.id = employees.department_id', 'left')
            ->where('employees.id', $id)
            ->first();
    }
    
    /**
     * Get all employees with relations
     */
    public function getAllWithRelations()
    {
        return $this->select('employees.*, 
            departments.name as department_name, 
            locations.name as location_name,
            job_grades.name as job_grade_name,
            supervisors.first_name as supervisor_first_name,
            supervisors.last_name as supervisor_last_name')
            ->join('departments', 'departments.id = employees.department_id', 'left')
            ->join('locations', 'locations.id = employees.location_id', 'left')
            ->join('job_grades', 'job_grades.id = employees.job_grade_id', 'left')
            ->join('employees as supervisors', 'supervisors.id = employees.supervisor_id', 'left')
            ->orderBy('employees.first_name', 'ASC')
            ->findAll();
    }
    
    /**
     * Get employees by department
     */
    public function getByDepartment(int $departmentId)
    {
        return $this->where('department_id', $departmentId)
            ->where('is_active', 1)
            ->findAll();
    }
    
    /**
     * Get direct reports (employees supervised by this employee)
     */
    public function getDirectReports(int $employeeId)
    {
        return $this->where('supervisor_id', $employeeId)
            ->where('is_active', 1)
            ->findAll();
    }
    
    /**
     * Get supervisor
     */
    public function getSupervisor(int $employeeId)
    {
        $employee = $this->find($employeeId);
        if ($employee && $employee['supervisor_id']) {
            return $this->find($employee['supervisor_id']);
        }
        return null;
    }
    
    /**
     * Get employee by email
     */
    public function getByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Get employee by employee number
     */
    public function getByEmployeeNumber(string $employeeNumber)
    {
        return $this->where('employee_number', $employeeNumber)->first();
    }
    
    /**
     * Search employees
     */
    public function search(string $keyword)
    {
        return $this->groupStart()
            ->like('first_name', $keyword)
            ->orLike('last_name', $keyword)
            ->orLike('employee_number', $keyword)
            ->orLike('email', $keyword)
            ->groupEnd()
            ->where('is_active', 1)
            ->findAll();
    }
    
    /**
     * Get active employees count
     */
    public function countActive()
    {
        return $this->where('is_active', 1)->countAllResults();
    }
    
    /**
     * Get employees by employment status
     */
    public function getByStatus(string $status)
    {
        return $this->where('employment_status', $status)->findAll();
    }
}
