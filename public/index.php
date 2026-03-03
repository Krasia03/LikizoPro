<?php

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        break;

    case 'testing':
    case 'production':
        ini_set('display_errors', '0');
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 */
$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 */
$application_folder = 'app';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 */
$view_folder = '';

// Set the current directory correctly for includes
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', __DIR__ . '/' . $system_path . '/');
define('FCPATH', __DIR__ . '/');
define('SYSDIR', basename(BASEPATH));
define('APPPATH', BASEPATH . $application_folder . '/');
define('VIEWPATH', APPPATH . 'Views' . '/');

// Load the paths config file
require_once APPPATH . 'Config/Paths.php';

$paths = new Config\Paths();

// Location of the Composer autoload file
$composer_autoload = __DIR__ . '/vendor/autoload.php';

if (!file_exists($composer_autoload)) {
    echo 'Composer auto-load missing. Run "composer install".' . PHP_EOL;
    exit(1);
}

// Load the autoloader
require_once $composer_autoload;

// Load the bootstrap file
require_once BASEPATH . 'CodeIgniter.php';
