<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

/** Default page configuration ... */

// DEF_PLATFORM = folder of home page
defined('DEF_PLATFORM') OR define('DEF_PLATFORM', 'home');

// DEF_CONTROLLER = name of controller class
defined('DEF_CONTROLLER') OR define('DEF_CONTROLLER', 'Home');

// DEF_ACTION = name of controller class function
defined('DEF_ACTION') OR define('DEF_ACTION', 'index');

//===============================================

// Save The Keys In Your Configuration File
defined('MAIN_KEY') OR define('MAIN_KEY','Lk5Uz3slx3BrAghS1aaW5AYgWZRV0tIX5eI0yPchFz4=');
defined('ASSURED_KEY') OR define('ASSURED_KEY','EZ44mFi3TlAey1b2w4Y7lVDuqO+SRxGXsa7nctnr/JmMrA2vN6EJhrvdVZbxaQs5jpSe34X3ejFK/o9+Y5c83w==');

//===============================================

// My custom plan status
defined('PLAN_STATUS_ACTIVATE') OR define('PLAN_STATUS_ACTIVATE', 1);
defined('PLAN_STATUS_DEACTIVATE') OR define('PLAN_STATUS_DEACTIVATE', 2);
defined('PLAN_STATUS_FULL') OR define('PLAN_STATUS_FULL', 3);
defined('PLAN_STATUS_CLOSED') OR define('PLAN_STATUS_CLOSED', 4);

// My custom payment status
defined('OWN_PAYMENT_STATUS_SUCCESSFUL') OR define('OWN_PAYMENT_STATUS_SUCCESSFUL', 1);
defined('OWN_PAYMENT_STATUS_FAILED') OR define('OWN_PAYMENT_STATUS_FAILED', 0);
defined('OWN_PAYMENT_STATUS_NOT_PAYED') OR define('OWN_PAYMENT_STATUS_NOT_PAYED', -9);
defined('OWN_PAYMENT_STATUS_WAIT') OR define('OWN_PAYMENT_STATUS_WAIT', -8);

// My custom payment wait
defined('OWN_WAIT_TIME') OR define('OWN_WAIT_TIME', 60 * 60);

//===============================================

return array(
    // Use to statically route from a [platform/controller/action/params] to another [platform/controller/action/params]
    // Support RegEx for mapping
    //$routes = array(
    //    'hello/*' => 'index'
    //);
    'routes' => array(
        'admin' => 'admin/admin',
        'admin/(:any)' => 'admin/admin/$1',
        'admin/(:any)/(:any)' => 'admin/admin/$1/$2',
        'admin/(:any)/(:any)/(:any)' => 'admin/admin/$1/$2/$3',
    ),

    //===============================================
    // Stay in default route and DOESN'T route. Useful for maintenance!
    'always_stay_in_default_route' => false,

    //===============================================
    // Maintenance password to see website just developer.
    // Give this to end of url as parameter to validate and access that url
    'maintenance_password' => "",

    //===============================================
    // Define languages
    // Index 0 in array below will be default language for site
    //$languages = ['fa', 'en'];
    'languages' => ['fa'],

    //===============================================
    // Acceptable values are
    // For development mode [development|dev]
    // For release mode [release|rel]
    // For semi development! mode(semi development mode just shut E_NOTICE down) [semi_development|semi_dev]
    'mode' => 'development',

    //===============================================
    // Never delete this default notfound
    'default_notfound' => 'errors/fe/404',

    //===============================================
    'admin_notfound' => 'errors/be/404',

    //===============================================
    // Captcha session name to access in controller(s)
    'captcha_session_name' => 'captcha_text',
);
