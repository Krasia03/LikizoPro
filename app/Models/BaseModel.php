<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $useSoftDeletes = false;
    
    protected $useTimestamps = true;
    
    protected $createdField = 'created_at';
    
    protected $updatedField = 'updated_at';
    
    protected $dateFormat = 'datetime';
    
    protected $insertID = 0;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get current user ID from session
     */
    protected function getCurrentUserId(): ?int
    {
        $session = session();
        return $session->get('user_id');
    }
    
    /**
     * Get current user role ID
     */
    protected function getCurrentUserRoleId(): ?int
    {
        $session = session();
        return $session->get('role_id');
    }
    
    /**
     * Get current user employee ID
     */
    protected function getCurrentUserEmployeeId(): ?int
    {
        $session = session();
        return $session->get('employee_id');
    }
    
    /**
     * Format response
     */
    protected function formatResponse($data = null, $message = '', $success = true)
    {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];
    }
    
    /**
     * Paginate with additional metadata
     */
    public function paginateWithMeta($perPage = 20, $select = '*')
    {
        $page = (int) ($_GET['page'] ?? 1);
        $page = $page > 0 ? $page : 1;
        
        $total = $this->countAll();
        $offset = ($page - 1) * $perPage;
        
        $data = $this->select($select)
            ->findAll($perPage, $offset);
        
        return [
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage),
                'has_next' => $page < ceil($total / $perPage),
                'has_prev' => $page > 1,
            ]
        ];
    }
}
