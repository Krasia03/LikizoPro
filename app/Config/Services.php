<?php

namespace Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function jwt($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('jwt');
        }
        
        $config = config('Auth');
        return new \App\Services\JwtService($config->jwtKey, $config->jwtAlgorithm, $config->jwtExpiry);
    }
    
    public static function rbac($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('rbac');
        }
        
        return new \App\Services\RBACService();
    }
    
    public static function auditLog($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('auditLog');
        }
        
        return new \App\Services\AuditLogService();
    }
}
