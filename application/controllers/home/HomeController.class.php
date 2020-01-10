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
            $_SESSION['home_panel_namespace'] = 'home_hva_ms_7472';
            $this->auth->setNamespace($_SESSION['home_panel_namespace'])->setExpiration(365 * 24 * 60 * 60);
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
        $model = new Model();

        $this->data['topEvents'] = $model->select_it(null, 'plans', [
            'title', 'slug', 'capacity', 'base_price', 'image', 'start_at', 'end_at', 'active_at', 'deactive_at', 'place', 'status',
        ], 'publish=:pub', ['pub' => 1], null, ['id DESC'], 3);
        //-----
        $this->data['feedback'] = $model->select_it(null, 'site_feedback', '*', 'show_in_page=:sip', ['sip' => 1]);
        //-----
        $this->data['helpfulLinks'] = $model->select_it(null, 'helpful_links');
        //-----

        // Newsletter form
        $this->data['newsletterErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addNewletter');
        $form->setFieldsName(['mobile'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function () use ($model, $form) {
                $form->isRequired(['mobile'], 'فیلدهای موبایل اجباری می‌باشد.')
                    ->validatePersianMobile('mobile');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = true;
                if(!$model->is_exist('newsletters', 'mobile=:mobile', ['mobile' => $values['mobile']])) {
                    $res = $model->insert_it('newsletters', [
                        'mobile' => trim($values['mobile']),
                    ]);
                }

                if (!$res) {
                    $form->setError('خطا در انجام عملیات!');
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $form->checkForm()->isSuccess();
        if ($form->isSubmit()) {
            if ($res) {
                $this->data['newsletterSuccess'] = 'موبایل شما با موفقیت ثبت شد.';
            } else {
                $this->data['newsletterErrors'] = $form->getError();
            }
        }

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'صفحه اصلی');

        $this->_render_page([
            'pages/fe/index',
        ]);
    }

    public function eventsAction()
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'صفحه اصلی');

        $this->_render_page([
            'pages/fe/all-events',
        ]);
    }

    public function eventAction($param)
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

    public function blogAction($param)
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'بلاگ');

        $this->_render_page([
            'pages/fe/blog',
        ]);
    }

    //------------------------------
    //------ Register & Login ------
    //------------------------------

    protected function _register()
    {
        if ($this->auth->isLoggedIn()) {
            $this->error->show_404(null, $this->data);
        }

        $model = new Model();

        $this->data['registerErrors'] = [];
        $this->data['registerValues'] = [];

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
                $form->validatePassword('password', 2, 'رمز عبور باید شامل حروف و اعداد باشد.');

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
                    'image' => PROFILE_DEFAULT_IMAGE
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
            $this->data['registerErrors'] = $form->getError();
            $this->data['registerValues'] = $form->getValues();
        }
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