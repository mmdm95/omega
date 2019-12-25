<?php

use HAuthentication\Auth;
use HAuthentication\HAException;

defined('BASE_PATH') OR exit('No direct script access allowed');

class HomeController extends HController
{
    //-----
    protected $auth;
    protected $setting;
    protected $data = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->library('HAuthentication/Auth');
        try {
            $this->auth = new Auth();
            $this->auth->setNamespace('homePanel')->setExpiration(365 * 24 * 60 * 60);
            $_SESSION['home_panel_namespace'] = 'home_hva_ms_7472';
        } catch (HAException $e) {
            echo $e;
        }

        // Load file helper .e.g: read_json, etc.
        $this->load->helper('file');

        if (!is_ajax()) {
            // Read settings once
            $this->setting = read_json(CORE_PATH . 'config.json');
            $this->data['setting'] = $this->setting;
        }

        // Read identity and store in data to pass in views
        $this->data['auth'] = $this->auth;
        $this->data['identity'] = $this->auth->getIdentity();

        if (!is_ajax()) {
            // Config(s)
            $this->data['favIcon'] = $this->setting['main']['favIcon'] ? base_url($this->setting['main']['favIcon']) : '';
            $this->data['logo'] = $this->setting['main']['logo'] ?? '';
        }
    }

    public function indexAction()
    {

    }

    //-----

    private function _render_page($pages, $loadHeaderAndFooter = true)
    {
        if ($loadHeaderAndFooter) {
            $this->load->view('templates/fe/home-header-part', $this->data);
        }

        $allPages = is_string($pages) ? [$pages] : (is_array($pages) ? $pages : []);
        foreach ($allPages as $page) {
            $this->load->view($page, $this->data);
        }

        if ($loadHeaderAndFooter) {
            $this->load->view('templates/fe/home-js-part', $this->data);
            $this->load->view('templates/fe/home-end-part', $this->data);
        }
    }
}