<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class WebAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('logged_in')) {
            // Redirect to login
            return redirect()->to('/auth/login')->with('error', 'Please login to access this page');
        }
        
        // Check role-based access
        if ($arguments && !in_array($session->get('role_id'), $arguments)) {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to access this page');
        }
        
        return null;
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}
