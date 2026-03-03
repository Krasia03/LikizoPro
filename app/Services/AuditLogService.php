<?php

namespace App\Services;

use App\Models\AuditLogModel;

class AuditLogService
{
    private $auditLogModel;
    
    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }
    
    /**
     * Log an action
     */
    public function log(int $userId, string $action, string $entityType, int $entityId = null, 
        array $beforeState = null, array $afterState = null, string $userEmail = null)
    {
        return $this->auditLogModel->log($userId, $action, $entityType, $entityId, $beforeState, $afterState, $userEmail);
    }
    
    /**
     * Log login attempt
     */
    public function logLogin(int $userId, string $email, bool $success, string $ipAddress = null)
    {
        return $this->auditLogModel->log(
            $success ? $userId : null,
            $success ? 'login_success' : 'login_failed',
            'user',
            $success ? $userId : null,
            null,
            null,
            $email
        );
    }
    
    /**
     * Log CRUD operations
     */
    public function logCRUD(int $userId, string $action, string $entityType, int $entityId, 
        array $oldData = null, array $newData = null)
    {
        $actionMap = [
            'create' => "created {$entityType}",
            'update' => "updated {$entityType}",
            'delete' => "deleted {$entityType}",
        ];
        
        return $this->auditLogModel->log(
            $userId,
            $actionMap[$action] ?? $action,
            $entityType,
            $entityId,
            $oldData,
            $newData
        );
    }
    
    /**
     * Log leave request action
     */
    public function logLeaveRequest(int $userId, int $requestId, string $action, array $details = [])
    {
        return $this->auditLogModel->log(
            $userId,
            $action,
            'leave_request',
            $requestId,
            null,
            $details
        );
    }
    
    /**
     * Get entity history
     */
    public function getEntityHistory(string $entityType, int $entityId): array
    {
        return $this->auditLogModel->getByEntity($entityType, $entityId);
    }
    
    /**
     * Get user activity
     */
    public function getUserActivity(int $userId, int $limit = 100): array
    {
        return $this->auditLogModel->getByUser($userId, $limit);
    }
    
    /**
     * Search audit logs
     */
    public function search(array $filters, int $limit = 100): array
    {
        return $this->auditLogModel->search($filters, $limit);
    }
    
    /**
     * Get recent activity
     */
    public function getRecent(int $limit = 50): array
    {
        return $this->auditLogModel->getRecent($limit);
    }
}
