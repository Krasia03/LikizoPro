<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    public $baseURL = 'http://localhost:8080/';
    
    public $indexPage = 'index.php';
    
    public $uriProtocol = 'REQUEST_URI';
    
    public $defaultLocale = 'en';
    
    public $negotiateLocale = [];
    
    public $supportedLocales = ['en'];
    
    public $appTimezone = 'Africa/Dar_es_Salaam';
    
    public $charset = 'UTF-8';
    
    public $csrfProtection = 'cookie';
    
    public $CSRFTokenName = 'csrf_token_name';
    
    public $CSRFCookieName = 'csrf_cookie_name';
    
    public $CSRFExpire = 7200;
    
    public $CSRFRegenerate = true;
    
    public $CSRFExcludeURIs = ['api/*'];
    
    public $CSPEnabled = false;
    
    public $sessionDriver = 'CodeIgniter\Session\Handlers\DatabaseHandler';
    
    public $sessionCookieName = 'ci_session';
    
    public $sessionSavePath = 'ci_sessions';
    
    public $sessionMatchIP = false;
    
    public $sessionTimeToUpdate = 300;
    
    public $sessionRegenerateDestroy = false;
    
    public $cookiePrefix = '';
    
    public $cookieDomain = '';
    
    public $cookiePath = '/';
    
    public $cookieSecure = false;
    
    public $cookieHTTPOnly = true;
    
    public $cookieSameSite = 'Lax';
    
    public $standardizeNewlines = false;
    
    public $enableHooks = true;
    
    public $classSuffix = '';
    
    public $proxyIPs = [];
}
