<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends BaseModel
{
    protected $table = 'audit_logs';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'user_id', 'user_email', 'action', 'entity_type', 'entity_id',
        'before_state', 'after_state', 'ip_address', 'user_agent'
    ];
    
    /**
     * Log an action
     */
    public function log(int $userId, string $action, string $entityType, int $entityId = null, 
        array $beforeState = null, array $afterState = null, string $userEmail = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'user_email' => $userEmail,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'before_state' => $beforeState ? json_encode($beforeState) : null,
            'after_state' => $afterState ? json_encode($afterState) : null,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Get logs by entity
     */
    public function getByEntity(string $entityType, int $entityId)
    {
        return $this->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Get logs by user
     */
    public function getByUser(int $userId, int $limit = 100)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Get logs by action
     */
    public function getByAction(string $action, int $limit = 100)
    {
        return $this->where('action', $action)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Get recent logs
     */
    public function getRecent(int $limit = 50)
    {
        return $this->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Search logs
     */
    public function search(array $filters, int $limit = 100)
    {
        if (!empty($filters['user_id'])) {
            $this->where('user_id', $filters['user_id']);
        }
        
        if (!empty($filters['action'])) {
            $this->where('action', $filters['action']);
        }
        
        if (!empty($filters['entity_type'])) {
            $this->where('entity_type', $filters['entity_type']);
        }
        
        if (!empty($filters['start_date'])) {
            $this->where('created_at >=', $filters['start_date'] . ' 00:00:00');
        }
        
        if (!empty($filters['end_date'])) {
            $this->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }
        
        return $this->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
