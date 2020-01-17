<?php

use HAuthentication\Auth;

defined('BASE_PATH') OR exit('No direct script access allowed');

class Exceptions
{
    protected $controller;

    public function __construct()
    {
        $this->controller =& HController::get_instance();
    }

    public function show_404($notFoundConfigName = 'default_notfound', $data = [], $returnContent = false, $checkAdmin = false)
    {
        if(is_null($notFoundConfigName)) $notFoundConfigName = 'default_notfound';
        $path = $this->controller->config[$notFoundConfigName];
        URITracker::remove_last_uri();

        http_response_code(404);

        $this->controller->load->library('HAuthentication/Auth');

        try {
            $auth = new Auth();
            if ($checkAdmin && isset($_SESSION['admin_panel_namespace']) &&
                ($auth->setStorageType(AUTH::session)->setNamespace($_SESSION['admin_panel_namespace'])->isLoggedIn() ||
                    $auth->setStorageType(AUTH::cookie)->setNamespace($_SESSION['admin_panel_namespace'])->isLoggedIn())) {
                $data['identity'] = $auth->getIdentity();
                $this->controller->load->view($path, $data, $returnContent === true);
            } else {
                $this->controller->load->view($this->controller->config['default_notfound'], $data, $returnContent === true);
            }
        } catch (Exception $e) {
        }

        exit(0);
    }

    public function access_denied()
    {
        http_response_code(403);
        URITracker::remove_last_uri();
        $this->controller->load->view('errors/Err_AccessDenied');
        exit(0);
    }
}
