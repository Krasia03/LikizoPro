<?php

namespace Config;

use CodeIgniter\Router\Router;

/**
 * @var Router $router
 */

// --------------------------------------------------------------------
// Route Definitions
// --------------------------------------------------------------------

// We get a performance increase by not specifying default routes.

// Auth Routes
$routes->get('/', 'AuthController::login');
$routes->get('/auth/login', 'AuthController::login');
$routes->post('/auth/doLogin', 'AuthController::doLogin');
$routes->get('/auth/logout', 'AuthController::logout');

// Dashboard
$routes->get('/dashboard', 'DashboardController::index');

// Leave Requests
$routes->get('/leave_requests', 'LeaveRequestsController::index');
$routes->get('/leave_requests/my', 'LeaveRequestsController::my');
$routes->get('/leave_requests/create', 'LeaveRequestsController::create');
$routes->post('/leave_requests/store', 'LeaveRequestsController::store');
$routes->get('/leave_requests/view/(:num)', 'LeaveRequestsController::view/$1');
$routes->get('/leave_requests/submit/(:num)', 'LeaveRequestsController::submit/$1');
$routes->get('/leave_requests/cancel/(:num)', 'LeaveRequestsController::cancel/$1');

// Approvals
$routes->get('/approvals', 'ApprovalsController::index');
$routes->get('/approvals/view/(:num)', 'ApprovalsController::view/$1');
$routes->post('/approvals/approve/(:num)', 'ApprovalsController::approve/$1');
$routes->post('/approvals/reject/(:num)', 'ApprovalsController::reject/$1');
$routes->post('/approvals/return/(:num)', 'ApprovalsController::return/$1');

// Employees
$routes->get('/employees', 'EmployeesController::index');
$routes->get('/employees/create', 'EmployeesController::create');
$routes->post('/employees/store', 'EmployeesController::store');
$routes->get('/employees/view/(:num)', 'EmployeesController::view/$1');
$routes->get('/employees/edit/(:num)', 'EmployeesController::edit/$1');
$routes->post('/employees/update/(:num)', 'EmployeesController::update/$1');
$routes->get('/employees/delete/(:num)', 'EmployeesController::delete/$1');

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\API'], function ($routes) {
    // Auth
    $routes->post('auth/login', 'AuthApiController::login');
    $routes->post('auth/refresh', 'AuthApiController::refresh');
    $routes->post('auth/logout', 'AuthApiController::logout');
    $routes->get('auth/me', 'AuthApiController::me');
    
    // Leave
    $routes->get('leave-requests', 'LeaveApiController::index');
    $routes->post('leave-requests', 'LeaveApiController::create');
    $routes->get('leave-requests/(:num)', 'LeaveApiController::show/$1');
    $routes->post('leave-requests/(:num)/submit', 'LeaveApiController::submit/$1');
    $routes->post('leave-requests/(:num)/cancel', 'LeaveApiController::cancel/$1');
    $routes->get('leave-balances', 'LeaveApiController::balances');
});

// --------------------------------------------------------------------
// Additional Routing
// --------------------------------------------------------------------
// There will often be times that you need additional routing and you
// need it to be able to override any defaults in this file. Environment
// based routes is one such time. require() additional route files here
// to make that happen.
//
// You will have access to the $routes object within that file without
// needing to reload it.
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
