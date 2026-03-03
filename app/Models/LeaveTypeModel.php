<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaveTypeModel extends BaseModel
{
    protected $table = 'leave_types';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'name', 'code', 'description', 'category', 'color', 'icon',
        'is_paid', 'requires_attachment', 'allow_half_day', 'is_active'
    ];
    
    protected $validationRules = [
        'name' => 'required',
        'code' => 'required|is_unique[leave_types.code,id,{id}]',
        'category' => 'required|in_list[annual,sick,maternity,paternity,compassionate,study,unpaid,special]',
    ];
    
    /**
     * Get all active leave types
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    /**
     * Get leave type by code
     */
    public function getByCode(string $code)
    {
        return $this->where('code', $code)->first();
    }
    
    /**
     * Get leave types by category
     */
    public function getByCategory(string $category)
    {
        return $this->where('category', $category)->findAll();
    }
    
    /**
     * Get paid leave types
     */
    public function getPaid()
    {
        return $this->where('is_paid', 1)->where('is_active', 1)->findAll();
    }
    
    /**
     * Get leave types that require attachment
     */
    public function getRequiringAttachment()
    {
        return $this->where('requires_attachment', 1)->where('is_active', 1)->findAll();
    }
}
