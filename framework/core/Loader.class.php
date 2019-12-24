<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Loader
{
    public static $controller = null;

    public function __construct()
    {

        if (empty(self::$controller)) {
            self::$controller =& HController::get_instance();
        }

    }

    // Load view pages

    public function view($view, $data = [], $getContent = false, $loadOnce = true, $loadOrDie = false, $customExtension = 'php')
    {

        $view = str_replace('/', DS, str_replace('\\', DS, $view));

        extract($data, EXTR_PREFIX_SAME, "h");

        ob_start();

        if (!$loadOrDie) {
            if ($loadOnce) {
                include_once VIEW_PATH . "{$view}.{$customExtension}";
            } else {
                include VIEW_PATH . "{$view}.{$customExtension}";
            }
        } else {
            if ($loadOnce) {
                require_once VIEW_PATH . "{$view}.{$customExtension}";
            } else {
                require VIEW_PATH . "{$view}.{$customExtension}";
            }
        }

        $var = ob_get_contents();
        if ($getContent) {
            ob_end_clean();
            return $var;
        }

        return $this;

    }

    // Load library classes

    public function library($lib)
    {

        if (file_exists(LIB_PATH . "{$lib}.class.php")) {
            include_once LIB_PATH . "{$lib}.class.php";
        } else {
            include_once LIB_PATH . "{$lib}.php";
        }

        return $this;

    }

    // loader helper functions. Naming conversion is xxx_helper.php;

    public function helper($helper)
    {

        if (file_exists(HELPER_PATH . "{$helper}_helper.php")) {
            include_once HELPER_PATH . "{$helper}_helper.php";
        } else {
            include_once HELPER_PATH . "{$helper}.php";
        }

        return $this;

    }

}