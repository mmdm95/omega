<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

use HAuthentication\Auth;
use HAuthentication\HAException;
use HForm\Form;


class HomeController extends HController
{
    //-----
    protected $auth;
    protected $setting;
    protected $data = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->library('HForm/Form');
        $form = new Form();

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

        $this->data['form_token_register'] = $form->csrfToken('register');
    }

    public function indexAction()
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'صفحه اصلی');

        $this->_render_page([
            'pages/fe/index',
        ]);
    }

    public function registerAction()
    {
        if ($this->auth->isLoggedIn()) {
            $this->error->show_404(null, $this->data);
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['usrVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $form->setFieldsName(['mobile', 'password', 're_password', 'role'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['mobile', 'password', 're_password'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('users', 'username=:name AND active=:a',
                    ['name' => $values['mobile'], 'a' => 1])) {
                    $form->setError('این شماره تلفن وجود دارد. لطفا دوباره تلاش کنید.');
                }
                $form->isLengthInRange('password', 8, 16, 'تعداد رمز عبور باید بین ۸ تا ۱۶ کاراکتر باشد.');
                $form->validatePersianMobile('mobile');
                $form->validatePassword('password', 2);

            })->afterCheckCallback(function ($values) use ($model, $form) {
                $this->data['code'] = generateRandomString(6, GRS_NUMBER);
                $this->data['_username'] = $values['mobile'];
                $this->data['_password'] = trim($values['password']);

                $model->transactionBegin();
                $res2 = $model->delete_it('users', 'username=:u', ['u' => $values['mobile']]);
                $res = $model->insert_it('users', [
                    'activation_code' => $this->data['code'],
                    'username' => convertNumbersToPersian(trim($values['mobile']), true),
                    'password' => password_hash(trim($values['password']), PASSWORD_BCRYPT),
                    'ip_address' => get_client_ip_env(),
                    'created_on' => time(),
                    'active' => 0,
                    'image' => 'user.svg'
                ]);

                if (!$res || !$res2) {
                    $model->transactionRollback();
                    $form->setError('خطا در انجام عملیات!');
                } else {
                    $model->transactionComplete();
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }
        $res = $form->checkForm()->isSuccess();
        if ($res == 1) {
            $_SESSION['username_validation_sess'] = encryption_decryption(ED_ENCRYPT, $this->data['_username']);
            $_SESSION['password_validation_sess'] = encryption_decryption(ED_ENCRYPT, $this->data['_password']);

            // Send SMS code goes here

            // Unset data
            unset($this->data['mobile']);
            unset($this->data['code']);

            if (isset($_GET['back_url'])) {
                $this->redirect(base_url('verifyPhone?back_url=' . $_GET['back_url']));
            }
            $this->redirect(base_url('verifyPhone'));
        } else if ($res == 2) {
            $this->data['errors'] = $form->getError();
            $this->data['usrVals'] = $form->getValues();
        }

    }

    public function eventsAction()
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'صفحه اصلی');

        $this->_render_page([
            'pages/fe/all-events',
        ]);
    }

    public function eventDetailAction()
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جزئیات طرح');

        $this->_render_page([
            'pages/fe/event-detail',
        ]);
    }

    public function userInformationAction()
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'فرم اطلاعات');

        $this->_render_page([
            'pages/fe/user-information',
        ]);
    }

    public function dashboardAction()
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'داشبورد');

        $this->_render_page([
            'pages/fe/user-profile',
        ]);
    }

    public function blogAction()
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'بلاگ');

        $this->_render_page([
            'pages/fe/blog',
        ]);
    }

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