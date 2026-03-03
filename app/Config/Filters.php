<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'      => \CodeIgniter\Filters\CSRF::class,
        'toolbar'   => \CodeIgniter\Filters\DebugToolbar::class,
        'adminauth' => \App\Filters\WebAuthFilter::class,
        'jwtauth'   => \App\Filters\JwtAuthFilter::class,
        'permission' => \App\Filters\PermissionFilter::class,
        'cors'      => \App\Filters\CorsFilter::class,
    ];
    
    public $requiredGroups = [];
    
    public $filters = [
        'adminauth' => ['except' => ['auth/login', 'auth/forgot', 'api/*']],
    ];
}
