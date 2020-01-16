<?php

use HAuthentication\Auth;
use HAuthentication\HAException;
use HForm\Form;


abstract class AbstractController extends HController
{
    protected $auth;
    protected $setting;
    protected $data = [];

    public function __construct()
    {
        parent::__construct();

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
    }

    public function logout()
    {
        if($this->auth->isLoggedIn()) {
            $this->auth->logout();
            $this->redirect(base_url('index'));
        } else {
            $this->error->show_404();
        }
    }

    //-------------------------------
    //----------- Captcha -----------
    //-------------------------------

    public function captchaAction($param)
    {
        if (isset($param[0])) {
            createCaptcha((string)$param[0]);
        } else {
            createCaptcha();
        }
    }

    //-------------------------------
    //------ Register & Login -------
    //-------------------------------

    protected function _register($param)
    {
        $this->data['registerErrors'] = [];
        $this->data['registerValues'] = [];

        if ($this->auth->isLoggedIn()) {
            return;
        }

        $model = new Model();

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_register'] = $form->csrfToken('register');
        $form->setFieldsName(['mobile', 'password', 're_password', 'role', 'registerCaptcha'])
            ->setDefaults('role', AUTH_ROLE_GUEST)
            ->setMethod('post', [], ['role']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form, $param) {
                $form->isRequired(['mobile', 'password', 're_password', 'role', 'registerCaptcha'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('users', 'username=:name AND active=:a',
                    ['name' => $values['mobile'], 'a' => 1])) {
                    $form->setError('این شماره تلفن وجود دارد، لطفا دوباره تلاش کنید.');
                }
                $form->isLengthInRange('password', 8, 16, 'تعداد رمز عبور باید بین ۸ تا ۱۶ کاراکتر باشد.');
                $form->validatePersianMobile('mobile');
                $form->validatePassword('password', 2, 'رمز عبور باید شامل حروف و اعداد باشد.');
                if ($values['role'] == AUTH_ROLE_GUEST) {
                    $form->setError('نقش انتخاب شده نامعتبر است.');
                }
                $config = getConfig('config');
                if (!isset($config['captcha_session_name']) ||
                    !isset($_SESSION[$config['captcha_session_name']][$param['captcha']]) ||
                    !isset($param['captcha']) ||
                    encryption_decryption(ED_DECRYPT, $_SESSION[$config['captcha_session_name']][$param['captcha']]) != $values['registerCaptcha']) {
                    $form->setError('کد وارد شده با کد تصویر مغایرت دارد. دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $this->data['code'] = generateRandomString(6, GRS_NUMBER);
                $this->data['_username'] = $values['mobile'];
                $this->data['_password'] = trim($values['password']);

                $model->transactionBegin();
                $res2 = $model->delete_it('users', 'username=:u', ['u' => $values['mobile']]);
                $res = $model->insert_it('users', [
                    'activation_code' => $this->data['code'],
                    'username' => convertNumbersToPersian(trim($values['mobile']), true),
                    'password' => password_hash(trim($values['password']), PASSWORD_DEFAULT),
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
        if ($form->isSubmit()) {
            if ($res) {
                $_SESSION['username_validation_sess'] = encryption_decryption(ED_ENCRYPT, $this->data['_username']);
                $_SESSION['password_validation_sess'] = encryption_decryption(ED_ENCRYPT, $this->data['_password']);

                // Send SMS code goes here

                // Unset data
                unset($this->data['mobile']);
                unset($this->data['code']);

                $message = 'در حال پردازش عملیات ورود';
                $delay = 1;
                if (isset($_GET['back_url'])) {
                    $this->redirect(base_url('verifyPhone?back_url=' . $_GET['back_url']), $message, $delay);
                }
                $this->redirect(base_url('verifyPhone'), $message, $delay);
            } else {
                $this->data['registerErrors'] = $form->getError();
                $this->data['registerValues'] = $form->getValues();
            }
        }
    }

    protected function _login($param)
    {
        $this->data['loginErrors'] = [];
        $this->data['loginValues'] = [];


        if ($this->auth->isLoggedIn()) {
            return;
        }

        $model = new Model();

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_login'] = $form->csrfToken('login');
        $form->setFieldsName(['username', 'password', 'remember', 'loginCaptcha'])
            ->setMethod('post', [], ['remember']);
        try {
            $form->afterCheckCallback(function ($values) use ($model, $form, $param) {
                $config = getConfig('config');
                if (!isset($config['captcha_session_name']) ||
                    !isset($_SESSION[$config['captcha_session_name']][$param['captcha']]) ||
                    !isset($param['captcha']) ||
                    encryption_decryption(ED_DECRYPT, $_SESSION[$config['captcha_session_name']][$param['captcha']]) != $values['loginCaptcha']) {
                    $form->setError('کد وارد شده با کد تصویر مغایرت دارد. دوباره تلاش کنید.');
                }
                // If there is no captcha error
                if (!count($form->getError())) {
                    $login = $this->auth->login($values['username'], $values['password'], $form->isChecked('remember'));
                    if (is_array($login)) {
                        $form->setError($login['err']);
                    }
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $form->checkForm()->isSuccess();
        if ($form->isSubmit()) {
            if ($res) {
                $message = 'در حال پردازش عملیات ورود';
                $delay = 1;
                if (isset($_GET['back_url'])) {
                    $this->redirect($_GET['back_url'], $message, $delay);
                }
                $this->redirect(base_url('user/profile'), $message, $delay);
            } else {
                $this->data['loginErrors'] = $form->getError();
                $this->data['loginValues'] = $form->getValues();
            }
        }
    }

    //-----

    protected function _render_page($pages, $loadHeaderAndFooter = true)
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