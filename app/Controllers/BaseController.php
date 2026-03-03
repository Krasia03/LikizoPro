<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class BaseController extends Controller
{
    protected $helpers = ['url', 'form', 'html'];
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Set app timezone
        date_default_timezone_set('Africa/Dar_es_Salaam');
    }
    
    /**
     * Render JSON response
     */
    protected function respond($data, int $statusCode = 200)
    {
        $response = service('response');
        $response->setStatusCode($statusCode);
        $response->setJSON($data);
        
        return $response;
    }
    
    /**
     * Success response
     */
    protected function successResponse($message = 'Success', $data = null, int $statusCode = 200)
    {
        return $this->respond([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
    
    /**
     * Error response
     */
    protected function errorResponse($message = 'Error', $errors = null, int $statusCode = 400)
    {
        return $this->respond([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
    
    /**
     * Check if user has permission
     */
    protected function hasPermission(string $permissionSlug): bool
    {
        $session = session();
        $roleId = $session->get('role_id');
        
        if (!$roleId) {
            return false;
        }
        
        $rbacService = new \App\Services\RBACService();
        return $rbacService->hasPermission($roleId, $permissionSlug);
    }
}
