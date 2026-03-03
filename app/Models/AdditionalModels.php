<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends BaseModel
{
    protected $table = 'departments';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = ['name', 'code', 'parent_id', 'manager_id', 'description', 'is_active'];
}

class LocationModel extends BaseModel
{
    protected $table = 'locations';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = ['name', 'code', 'address', 'country', 'timezone', 'is_active'];
}

class JobGradeModel extends BaseModel
{
    protected $table = 'job_grades';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = ['name', 'code', 'level', 'description', 'is_active'];
}

class WorkScheduleModel extends BaseModel
{
    protected $table = 'work_schedules';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'name', 'code', 'monday_start', 'monday_end', 'tuesday_start', 'tuesday_end',
        'wednesday_start', 'wednesday_end', 'thursday_start', 'thursday_end',
        'friday_start', 'friday_end', 'saturday_start', 'saturday_end',
        'sunday_start', 'sunday_end', 'is_default', 'is_active'
    ];
}

class HolidayModel extends BaseModel
{
    protected $table = 'holidays';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = ['name', 'date', 'location_id', 'is_recurring', 'is_active'];
    
    public function getForYear(int $year, int $locationId = null)
    {
        $this->where('is_active', 1);
        $this->groupStart();
        $this->where('is_recurring', 1);
        $this->orWhere("YEAR(date)", $year);
        $this->groupEnd();
        
        if ($locationId) {
            $this->groupStart();
            $this->where('location_id', null);
            $this->orWhere('location_id', $locationId);
            $this->groupEnd();
        }
        
        return $this->findAll();
    }
}

class LeaveRequestDayModel extends BaseModel
{
    protected $table = 'leave_request_days';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = ['leave_request_id', 'date', 'day_type', 'is_counted', 'hours'];
}
