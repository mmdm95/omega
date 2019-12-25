<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Framework
{
    // Array for all parameters in URL after routing.

    private $params = [];

    private $lang = [];

    private $url = null;

    private $method_has_action = true;

    private $allow_route = true;

    private $maintenance_password = null;

    private $staticRoutes = null;

    //

    protected $glob;

    public function __construct()
    {
        global $GLOBALS;
        $this->glob =& $GLOBALS;

        $this->url = new stdClass();
    }

    public function run()
    {

        $this->init();

        $this->checkUrl();

        $this->autoload();

        $this->dispatch();

    }

    // Initialization.

    private function init()
    {
        // Start session if it's not started

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // ini_set('max_execution_time', 600); //600 seconds = 10 minutes

        /**
         * This code is directly from CI framework (thanks to them)
         * Set default charset to utf-8
         */
        $charset = "UTF-8";
        ini_set('default_charset', $charset);

        if (extension_loaded('mbstring')) {
            define('MB_ENABLED', TRUE);
            // mbstring.internal_encoding is deprecated starting with PHP 5.6
            // and it's usage triggers E_DEPRECATED messages.
            @ini_set('mbstring.internal_encoding', $charset);
            // This is required for mb_convert_encoding() to strip invalid characters.
            // That's utilized by CI_Utf8, but it's also done for consistency with iconv.
            mb_substitute_character('none');
        } else {
            define('MB_ENABLED', FALSE);
        }

        // There's an ICONV_IMPL constant, but the PHP manual says that using
        // iconv's predefined constants is "strongly discouraged".
        if (extension_loaded('iconv')) {
            define('ICONV_ENABLED', TRUE);
            // iconv.internal_encoding is deprecated starting with PHP 5.6
            // and it's usage triggers E_DEPRECATED messages.
            @ini_set('iconv.internal_encoding', $charset);
        } else {
            define('ICONV_ENABLED', FALSE);
        }

        if (
            defined('PREG_BAD_UTF8_ERROR')                // PCRE must support UTF-8
            && (ICONV_ENABLED === TRUE OR MB_ENABLED === TRUE)  // iconv or mbstring must be installed
        ) {
            define('UTF8_ENABLED', TRUE);
        } else {
            define('UTF8_ENABLED', FALSE);
        }
        //

        // Define path constants
        define("ROOT", getcwd() . DS);

        define('PROTOCOL', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://");

        define("SERVER_IP", $_SERVER['SERVER_ADDR']);
        define("REMOTE_IP", $_SERVER['REMOTE_ADDR']);

        define("CONFIG_PATH", APP_PATH . "config" . DS);

        define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);

        define("MODEL_PATH", APP_PATH . "models" . DS);

        define("VIEW_PATH", APP_PATH . "views" . DS);

        define("CORE_PATH", BASE_PATH . "core" . DS);

        define("FONTS_PATH", BASE_PATH . "fonts" . DS);

        define('DB_PATH', BASE_PATH . "database" . DS);

        define("LIB_PATH", BASE_PATH . "libraries" . DS);

        define("HELPER_PATH", BASE_PATH . "helpers" . DS);

        define('BASE_URL', PROTOCOL . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']
            . str_replace($_SERVER['DOCUMENT_ROOT'],
                '',
                str_replace('\\',
                    '/',
                    str_replace(trim(BASE_PATH, DS),
                        '',
                        dirname(__DIR__)))));

        define('ASSET_ROOT', BASE_URL . PUBLIC_PATH);

        define("UPLOAD_PATH", PUBLIC_PATH . "uploads" . DS);

        // Set timezone to [America/New_York]

        date_default_timezone_set('Asia/Tehran');

        // Load configuration file(s)

        global $HConfig;

        $HConfig['h_config']['COMMON_DATA'] = include CONFIG_PATH . "config.php";
        $HConfig['h_config']['DB_DATA'] = include CONFIG_PATH . "dbConfig.php";
        $HConfig['h_config']['AUTH_DATA'] = json_encode(include CONFIG_PATH . "authConfig.php");

        // Load global helper file

        require_once HELPER_PATH . "common_helper.php";

        require_once CORE_PATH . "URITracker.class.php";

        // Set some config parameters

        if (isset($HConfig['h_config']['COMMON_DATA']['always_stay_in_default_route']) &&
            $HConfig['h_config']['COMMON_DATA']['always_stay_in_default_route']) {
            $this->allow_route = false;
        }

        if (isset($HConfig['h_config']['COMMON_DATA']['maintenance_password']) &&
            trim($HConfig['h_config']['COMMON_DATA']['maintenance_password']) != '') {
            $this->maintenance_password = $HConfig['h_config']['COMMON_DATA']['maintenance_password'];
        }

        define('LANGUAGES', json_encode(isset($HConfig['h_config']['COMMON_DATA']['languages']) ? $HConfig['h_config']['COMMON_DATA']['languages'] : null));

        define('ENVIRONMENT', isset($HConfig['h_config']['COMMON_DATA']['mode']) ? $HConfig['h_config']['COMMON_DATA']['mode'] : 'rel');

        $this->staticRoutes = isset($HConfig['h_config']['COMMON_DATA']['routes']) && count($HConfig['h_config']['COMMON_DATA']['routes']) ? $HConfig['h_config']['COMMON_DATA']['routes'] : null;

        // Set exception parameters

        define('E_FATAL', E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR |
            E_COMPILE_ERROR | E_RECOVERABLE_ERROR);

        // Set develop condition

        // These are for showing errors

        if (strtolower(ENVIRONMENT) == 'development' || strtolower(ENVIRONMENT) == 'dev') {
            error_reporting(E_ALL);
            @ini_set("display_errors", 1);

            //Custom error handling vars
            define('DISPLAY_ERRORS', TRUE);
            define('ERROR_REPORTING', E_ALL | E_STRICT);
            define('LOG_ERRORS', TRUE);
        } else if (strtolower(ENVIRONMENT) == 'release' || strtolower(ENVIRONMENT) == 'rel') {
            error_reporting(0);
            @ini_set("display_errors", 0);

            //Custom error handling vars
            define('DISPLAY_ERRORS', FALSE);
            define('ERROR_REPORTING', ~E_ALL);
            define('LOG_ERRORS', FALSE);
        }

        if (isset($HConfig['h_config']['DB_DATA']['databases'])) {
            define('DATABASE_STR', json_encode($HConfig['h_config']['DB_DATA']['databases']));
        }

        // Load core classes

        require_once CORE_PATH . 'HException.php';

        require_once CORE_PATH . "HController.class.php";

        require_once CORE_PATH . "Loader.class.php";

        require_once CORE_PATH . "Asset.class.php";

        require_once CORE_PATH . "Exceptions.class.php";

        require_once DB_PATH . "HModel.class.php";

        // Store all languages in private variable

        $this->lang = json_decode(LANGUAGES);
    }

    private function get_url()
    {
        $extension_pattern = '/\\.[^.\\s]{3,4}$/';

        $language = '';
        $platform = '';
        $controller = '';
        $action = '';

        // Check if allow routing is disabled and maintenance is null(stays in default route)
        // or there is no url, go to default route

        if ((!$this->allow_route && is_null($this->maintenance_password)) || (!count($this->url->rsegments))) {
            return $this->setDefaultRoute();
        }

        // Check for static routes

        $this->url->rsegments = $this->staticRoutes();

        // Cut language from url

        if (in_array($this->url->rsegments[0], $this->lang)) {
            $language = array_shift($this->url->rsegments);
        }

        // Cut platform from url or set it to default platform

        if (count($this->url->rsegments)) {
            $platform = $this->url->rsegments[0];
        }

        if (trim($platform) != '' && $this->existsPlatform($platform)) {
            if (count($this->url->rsegments)) array_shift($this->url->rsegments);
        } else {
            $platform = DEF_PLATFORM;
        }

        // Cut controller from url or set it to default controller

        if (count($this->url->rsegments)) {
            $controller = $this->url->rsegments[0];
        }

        if ($this->existsController($platform, $controller)) {
            if (count($this->url->rsegments)) array_shift($this->url->rsegments);
        } else {
            $controller = DEF_CONTROLLER;
        }

        // Cut action from url or set it to default action

        if (count($this->url->rsegments)) {
            $action = $this->url->rsegments[0];
        } else {
            $action = DEF_ACTION;
        }

        if (!$this->existsAction($platform, $controller, $action)) {
            // Set url of 404 page

            $platform = '';
            $controller = 'Errors';
            $action = 'index';
        }

        // Extra parameters

        $params = $this->url->rsegments ? array_values($this->url->rsegments) : [];

        // Refill rsegments with new url values(original values are safe in segments part).

        $this->url->rsegments = [$language, $platform, $controller, $action, $params];

        // Check for maintenance password to go inside website
        // We check this here because we need the last parameter of url

        if (!isset($_SESSION['maintenance_inside_session']) && !is_null($this->maintenance_password) && !$this->allow_route) {
            $paramsCount = count($params);
            $lastParamIndex = $paramsCount - 1;
            $testing_password = $paramsCount ? $params[$lastParamIndex] : '';

            // If maintenance password isn't correct

            if ($testing_password != $this->maintenance_password) {
                $this->allow_route = false;
                return $this->setDefaultRoute();
            }

            // Otherwise it's correct and must unset last param

            unset($params[$lastParamIndex]);
            $_SESSION['maintenance_inside_session'] = true;
        }

        return [
            'platform' => $platform,
            'controller' => $controller,
            'action' => $action,
            'params' => $params,
            'language' => $language
        ];
    }

    private function parseUrl()
    {
        $url = [];

        if (isset($_GET['url'])) {
            $url = array_map('trim', explode('/', urldecode(filter_var(urlencode(rtrim($_GET['url'], '/')), FILTER_SANITIZE_URL))));
        }

        return $url;
    }

    private function setDefaultRoute()
    {
        $extension_pattern = '/\\.[^.\\s]{3,4}$/';

        // Extra parameters

        $params = $this->url->rsegments && count($this->url->rsegments) ? array_values($this->url->rsegments) : [];

        $language = isset($this->lang[0]) ?: '';

        $platform = DEF_PLATFORM ?: '';
        $controller = DEF_CONTROLLER ?: 'Errors';
        $action = DEF_ACTION ?: 'index';

        // Check if any of p/c/a is not exists then go to not found page

        if ((trim($platform) != '' && !$this->existsPlatform($platform)) || !$this->existsController($platform, $controller) || !$this->existsAction($platform, $controller, $action)) {
            $platform = '';
            $controller = 'Errors';
            $action = 'index';
        }

        // Refill rsegments with new url values(original values are safe in segments part).

        $this->url->rsegments = [$language, $platform, $controller, $action, $params];

        // Check static routing in default route too

        $this->url->rsegments = $this->staticRoutes();

        return [
            'platform' => $platform,
            'controller' => $controller,
            'action' => $action,
            'params' => $params,
            'language' => $language
        ];
    }

    // Static route for some reasons. Who knows

    private function staticRoutes()
    {
        $result = $this->url->rsegments;

        if (!isset($this->staticRoutes) || !is_array($this->staticRoutes)) {
            return $result;
        }

        $url = implode('/', $this->url->segments);

        $routesKey = array_map(array(__CLASS__, 'replace'), array_keys($this->staticRoutes));
        $routesVal = array_map(array(__CLASS__, 'replace'), array_values($this->staticRoutes));
        $routes = array_combine($routesKey, $routesVal);

        foreach ($routes as $from => $to) {
            // The following lines of code are exactly from CI framework
            // to convert wildcards(with a little change actually ☺).

            // Convert wildcards to RegEx
            $from = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $from);

            // Does the RegEx match?
            if (preg_match('#^' . $from . '$#', $url, $matches)) {
                // Are we using callbacks to process back-references?
                if (!is_string($to) && is_callable($to)) {
                    // Remove the original string from the matches array.
                    array_shift($matches);

                    // Execute the callback using the values in matches as its parameters.
                    $to = call_user_func_array($to, $matches);
                } // Are we using the default routing method for back-references?
                elseif (strpos($to, '$') !== FALSE && strpos($from, '(') !== FALSE) {
                    $to = preg_replace('#^' . $from . '$#', $to, $url);
                }

                $result = explode('/', $to);
            }
        }

        return $result;
    }

    private function existsPlatform($platform)
    {
        return file_exists(CONTROLLER_PATH . $platform) && is_dir(CONTROLLER_PATH . $platform);
    }

    private function existsController($platform, $controller)
    {
        return trim($controller) != '' && file_exists(rtrim(CONTROLLER_PATH . $platform, DS) . DS . ucfirst($controller) . 'Controller.class.php');
    }

    private function existsAction($platform, $controller, $action)
    {
        $extension_pattern = '/\\.[^.\\s]{3,4}$/';

        if (trim($action) != '') {
            if ((trim($platform) != '' && !$this->existsPlatform($platform)) || !$this->existsController($platform, $controller)) {
                return false;
            }

            include_once rtrim(CONTROLLER_PATH . $platform, DS) . DS . ucfirst($controller) . 'Controller.class.php';

            $class_name = ucfirst($controller) . 'Controller';
            $action = preg_replace($extension_pattern, '', $action);
            if (method_exists($class_name, strtolower($action) . 'Action')) {
                // This will remove action that is exists from rsegments
                if (count($this->url->rsegments)) array_shift($this->url->rsegments);
                $this->method_has_action = true;
                return true;
            } else if (method_exists($class_name, strtolower($action))) {
                // This will remove action that is exists from rsegments
                if (count($this->url->rsegments)) array_shift($this->url->rsegments);
                $this->method_has_action = false;
                return true;
            }
        }

        return false;
    }

    private function replace($v)
    {
        if (is_string($v)) {
            return trim(str_replace(' ', '', trim(str_replace('\\', '/', $v), '/')));
        }
        return '';
    }

    private function checkUrl()
    {
        // Parse url to get [lang/[p[/[c/[a/[params]]]]]] (All are optional except action(a))

        $this->url->rsegments = $this->url->segments = $this->parseUrl();

        // Define platform, controller, action, parameters, for example:

        //** index.php?p=admin&c=Goods&a=add **//
        // index.php?url=admin/Goods/add/etc.

        $url_arr = $this->get_url();

        define("PLATFORM", $url_arr['platform']);

        define("CONTROLLER", $url_arr['controller']);

        define("ACTION", $url_arr['action']);

        $this->params = $url_arr['params'];

        define("LANGUAGE", $url_arr['language']);

        define("CURR_CONTROLLER_PATH", CONTROLLER_PATH . PLATFORM . DS);

        define("CURR_VIEW_PATH", VIEW_PATH . PLATFORM . DS);

//        URITracker::reset_tracks();
        if($url_arr['controller'] != 'Errors') {
            // Record uri and url
            URITracker::add_uri(base_url($url_arr['platform'] . DS . $url_arr['controller'] . DS . $url_arr['action'] . DS . implode(DS, $url_arr['params'])));
        }

//        var_dump(URITracker::get_tracks());
    }

    private function autoload()
    {
        spl_autoload_register(array(__CLASS__, 'load'));
    }

    // Define a custom load method

    private function load($classname)
    {

        // Here simply autoload app’s controller and model classes

        if (mb_strtolower(substr($classname, -10), 'UTF8') == "controller") {

            // Controller

            if (PLATFORM != '') {
                require_once CURR_CONTROLLER_PATH . ucfirst($classname) . ".class.php";
            } else {
                require_once CONTROLLER_PATH . ucfirst($classname) . ".class.php";
            }

        } elseif (mb_strtolower(substr($classname, -5), 'UTF8') == "model") {

            // Model

            require_once MODEL_PATH . "$classname.class.php";

        }

    }

    // Routing and dispatching

    private function dispatch()
    {

        // Instantiate the controller class and call its action method

        $controller_name = CONTROLLER . "Controller";

        if ($this->method_has_action) {
            $action_name = ACTION . "Action";
        } else {
            $action_name = ACTION;
        }

        $controller = new $controller_name;

        $controller->$action_name($this->params);

    }
}