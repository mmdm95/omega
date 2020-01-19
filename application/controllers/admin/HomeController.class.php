<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

use HAuthentication\Auth;
use HAuthentication\HAException;
use HForm\Form;
use voku\helper\AntiXSS;


class HomeController extends HController
{
    private $auth;
    private $data = [];
    private $setting;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('HAuthentication/Auth');
        try {
            $this->auth = new Auth();
            $_SESSION['admin_panel_namespace'] = 'admin_hva_ms_7472';
            $this->auth->setNamespace($_SESSION['admin_panel_namespace'])->setExpiration(365 * 24 * 60 * 60);
        } catch (HAException $e) {
            echo $e;
        }

        // Load file helper .e.g: read_json, etc.
        $this->load->helper('file');

        // Read settings once
        $this->setting = read_json(CORE_PATH . 'config.json');

        // Read identity and store in data to pass in views
        $this->data['auth'] = $this->auth;
        $this->data['identity'] = $this->auth->getIdentity();

        // Config(s)
        $this->data['favIcon'] = $this->setting['main']['favIcon'] ? base_url($this->setting['main']['favIcon']) : '';
        $this->data['logo'] = $this->setting['main']['logo'] ?? '';

//        $model = new Model();
//        $model->insert_it('users', [
//            'username' => 'godheeva@gmail.com',
//            'password' => password_hash('m9516271', PASSWORD_DEFAULT),
//            'ip_address' => get_client_ip_server(),
//            'email' => 'saeedgerami72@gmail.com',
//            'created_on' => time(),
//            'active' => '1',
//            'full_name' => 'سعید گرامی فر',
//            'image' => 'user-default.png',
//            'n_code' => '4420440392',
//        ]);
    }

    public function loginAction()
    {
        if ($this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/index'));
        }

        // For showing 404 page in ErrorController
        unset($_SESSION['admin_panel_namespace']);

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['loginVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('login');
        $form->setFieldsName(['username', 'password', 'remember'])
            ->setMethod('post', [], ['remember']);
        try {
            $form->afterCheckCallback(function ($values) use ($model, $form) {
                $login = $this->auth->login($values['username'], $values['password'], $form->isChecked('remember'), true,
                    'active=:active', ['active' => 1]);
                if (is_array($login)) {
                    $form->setError($login['err']);
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $form->checkForm()->isSuccess();
        if ($form->isSubmit()) {
            if ($res) {
                $this->redirect(base_url('admin/index'));
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['loginVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ورود');

        $this->load->view('pages/be/login', $this->data);
    }

    public function logoutAction()
    {
        if ($this->auth->isLoggedIn()) {
            $this->auth->logout();
            $this->redirect(base_url('admin/login'));
        } else {
            $this->redirect(base_url('index'));
        }
    }

    public function indexAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'داشبورد');
        $this->data['todayDate'] = jDateTime::date('l d F Y') . ' - ' . date('d F');

        $model = new Model();
        // Unread contact us
        $this->data['unreadContacts'] = $model->it_count('contact_us', 'status=:stat', ['stat' => 0]);
        // Static pages count
        $this->data['staticPages'] = $model->it_count('static_pages');
        // Users count
        $this->data['usersCount'] = $model->it_count('users');
        // Factors count
        $this->data['factorsCount'] = $model->it_count('factors');
        // Categories count
        $this->data['catsCount'] = $model->it_count('categories');
        // Comments count
        $this->data['commentsCount'] = $model->it_count('comments');

        $this->_render_page('pages/be/index');
    }

    public function editUserAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('users', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageUser'));
        }

        if ($this->data['identity']->id != $param[0]) {
            try {
                if (!$this->auth->isAllow('user', 3)) {
                    $this->error->access_denied();
                }

                // Prevent users with same or less graded id, access superior or equally graded of them
                $uRole = $model->select_it(null, 'users_roles', 'role_id', 'user_id=:uId', ['uId' => $param[0]]);
                if (!count($uRole) || ($uRole[0]['role_id'] <= $this->data['identity']->role_id)) {
                    $this->error->access_denied();
                }
            } catch (HAException $e) {
                echo $e;
            }
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['userVals'] = [];

        $this->data['userVals'] = $model->join_it(null, 'users AS u', 'users_roles AS r',
            '*', 'u.id=r.user_id',
            'u.id=:id', [
                'id' => $param[0],
            ], null, 'u.id DESC', null, null, false, 'LEFT')[0];

        $this->data['roles'] = $model->select_it(null, 'roles', '*', 'id>:id AND id!=:id2', ['id' => $this->data['identity']->role_id, 'id2' => AUTH_ROLE_GUEST]);

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editUser');
        $form->setFieldsName(['name', 'username', 'role', 'password', 'rePassword'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['username'], 'فیلدهای ضروری را خالی نگذارید.');
                $form->validate('!numeric|string', 'name', 'نام و نام خانوادگی باید از نوع رشته باشد.');

                if ($this->data['identity']->role_id <= AUTH_ROLE_ADMIN) {
                    $form->isIn('role', array_column($this->data['roles'], 'id'), 'نقش انتخاب شده وجود ندارد.');
                }

                if ((trim($values['password']) != '' || trim($values['rePassword']) != '') && $values['password'] != $values['rePassword']) {
                    $form->setError('رمز عبور با تکرار آن مغایرت دارد.');
                }

                if (trim($values['password']) != '') {
                    $form->isLengthInRange('password', 8, 16, 'پسورد باید حداقل ۸ و حداکثر ۱۶ رقم باشد.');
                    $form->validatePassword('password', 2, 'پسورد باید شامل حروف و اعداد انگلیسی باشد.');
                }

                if ($this->data['userVals']['username'] != $values['username'] &&
                    $model->is_exist('users', 'username=:username', ['username' => $values['username']])) {
                    $form->setError('این نام کاربری وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $model->transactionBegin();
                $res1 = $model->update_it('users', [
                    'username' => trim($values['username']),
                    'password' => trim($values['password']) != '' ? password_hash($values['password'], PASSWORD_BCRYPT) : $this->data['userVals']['password'],
                    'full_name' => trim($values['name'])
                ], 'id=:id', ['id' => $this->data['userVals']['id']]);

                if ($this->data['identity']->role_id < 3) {
                    $res2 = $model->update_it('user_role', [
                        'role_id' => $values['role']
                    ], 'user_id=:id', ['id' => $this->data['userVals']['id']]);
                } else {
                    $res2 = true;
                }

                if (!$res1 || !$res2) {
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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
            }
        }

        $this->data['userVals'] = $model->join_it(null, 'users AS u', 'users_roles AS ur',
            '*', 'u.id=ur.user_id', 'u.id=:id', ['id' => $param[0]]);
        if (!count($this->data['userVals'])) {
            $this->data['errors'][] = 'خطا در یافتن کاربر';
        } else {
            $this->data['userVals'] = $this->data['userVals'][0];
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش کاربر');

        $this->_render_page('pages/be/User/editUser');
    }

    public function manageUserAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        try {
            if (!$this->auth->isAllow('user', 2)) {
                $this->error->access_denied();
            }
        } catch (HAException $e) {
            echo $e;
        }

        $model = new Model();
        $this->data['users'] = $model->join_it(null, 'users AS u', 'users_roles AS r',
            ['u.id', 'u.username', 'u.full_name', 'u.created_on', 'u.active'], 'u.id=r.user_id',
            '(r.role_id>:id OR r.role_id=:id2) AND u.id!=:curId', [
                'id' => $this->data['identity']->role_id,
                'id2' => AUTH_ROLE_SUPER_USER,
                'curId' => $this->data['identity']->id,
            ], null, 'u.id DESC', null, null, false, 'LEFT');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده کاربران');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/User/manageUser');
    }

    public function deleteUserAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        try {
            if (!$this->auth->isAllow('user', 4)) {
                $this->error->access_denied();
            }
        } catch (HAException $e) {
            echo $e;
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'users';
        if (!isset($id) || $id == $this->data['identity']->id) {
            message('error', 200, 'کاربر نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'کاربر وجود ندارد.');
        }

        $uRole = $model->select_it(null, 'users_roles', 'role_id', 'user_id=:uId', ['uId' => $id]);
        if (!count($uRole) || $uRole[0]['role_id'] > $this->data['identity']->role_id) {
            $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
            if ($res) {
                message('success', 200, 'کاربر با موفقیت حذف شد.');
            }

            message('error', 200, 'عملیات با خطا مواجه شد.');
        } else {
            message('error', 200, 'عملیات غیر مجاز است!');
        }
    }

    public function activeDeactiveAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        try {
            if (!$this->auth->isAllow('user', 3)) {
                message('error', 403, 'دسترسی غیر مجاز');
            }
        } catch (HAException $e) {
            echo $e;
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $stat = $_POST['stat'];
        $table = 'users';
        if (!isset($id) || !isset($stat) || !in_array($stat, [0, 1])) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'کاربر وجود ندارد.');
        }

        $res = $model->update_it($table, ['stat' => $stat], 'id=:id', ['id' => $id]);
        if ($res) {
            if ($stat == 1) {
                message('success', 200, 'کاربر فعال شد.');
            } else {
                message('warning', 200, 'کاربر غیر فعال شد.');
            }
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addBlockedUserAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['blkVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addBlocked');
        $form->setFieldsName(['nCode'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['nCode'], 'فیلدهای ضروری را خالی نگذارید.')
                    ->validateNationalCode('nCode');
                if ($model->is_exist('block_list', 'n_code=:nCode', ['nCode' => $values['nCode']])) {
                    $form->setError('این کد ملی وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('block_list', [
                    'n_code' => trim($values['nCode']),
                ]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['blkVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن به لیست کاربران مسدود');

        $this->_render_page([
            'pages/be/BlockUser/addBlockedUser',
        ]);
    }

    public function manageBlockedUserAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['blocked'] = $model->select_it(null, 'block_list');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده کاربران مسدود شده');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/BlockUser/manageBlockedUser');
    }

    public function deleteBlockedUserAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'block_list';
        if (!isset($id)) {
            message('error', 200, 'آیتم نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'مورد وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'مورد با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addFeedbackAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['feedVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addFeedback');
        $form->setFieldsName(['image', 'full_name', 'feedback', 'show_in_page'])
            ->setDefaults('show_in_page', 'off')
            ->setMethod('post', [], ['show_in_page']);
        try {
            $form->beforeCheckCallback(function () use ($model, $form) {
                $form->isRequired(['image', 'full_name', 'feedback'], 'فیلدهای ضروری را خالی نگذارید.');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('site_feedback', [
                    'image' => trim($values['image']),
                    'full_name' => trim($values['full_name']),
                    'feedback' => trim($values['feedback']),
                    'show_in_page' => $form->isChecked('show_in_page') ? 1 : 0,
                    'created_at' => time(),
                ]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['feedVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن بازخورد');

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        $this->_render_page([
            'pages/be/Feedback/addFeedback',
            'templates/be/efm',
        ]);
    }

    public function editFeedbackAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('site_feedback', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageFeedback'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editFeedback');
        $form->setFieldsName(['image', 'full_name', 'feedback', 'show_in_page'])
            ->setDefaults('show_in_page', 'off')
            ->setMethod('post', [], ['show_in_page']);
        try {
            $form->beforeCheckCallback(function () use ($model, $form) {
                $form->isRequired(['image', 'full_name', 'feedback'], 'فیلدهای ضروری را خالی نگذارید.');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('site_feedback', [
                    'image' => trim($values['image']),
                    'full_name' => trim($values['full_name']),
                    'feedback' => trim($values['feedback']),
                    'show_in_page' => $form->isChecked('show_in_page') ? 1 : 0,
                ], 'id=:id', ['id' => $this->data['param'][0]]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['feedVals'] = $form->getValues();
            }
        }

        $this->data['feedVals'] = $model->select_it(null, 'site_feedback', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش بازخورد');

        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        $this->_render_page([
            'pages/be/Feedback/editFeedback',
            'templates/be/efm',
        ]);
    }

    public function manageFeedbackAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['feedback'] = $model->select_it(null, 'site_feedback');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده بازخورد');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Feedback/manageFeedback');
    }

    public function viewFeedbackAction($param)
    {

    }

    public function deleteFeedbackAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'site_feedback';
        if (!isset($id)) {
            message('error', 200, 'آیتم نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'بازخورد وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'بازخورد با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function publishFeedbackAction()
    {
        if (!$this->auth->isLoggedIn()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $stat = $_POST['stat'];
        $table = 'site_feedback';
        if (!isset($id) || !isset($stat) || !in_array($stat, [0, 1])) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'طرح وجود ندارد.');
        }

        $res = $model->update_it($table, ['show_in_page' => $stat], 'id=:id', ['id' => $id]);
        if ($res) {
            if ($stat == 1) {
                message('success', 200, 'نمایش بازخورد در صفحه اصلی فعال شد.');
            } else {
                message('warning', 200, 'نمایش بازخورد در صفحه اصلی غیرفعال شد.');
            }
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function manageNewsletterAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['newsletters'] = $model->select_it(null, 'newsletters');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده خبرنامه');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Newsletter/manageNewsletter');
    }

    public function deleteNewsletterAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'newsletters';
        if (!isset($id)) {
            message('error', 200, 'آیتم نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'مورد وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'مورد با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addCategoryAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['catVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addCategory');
        $form->setFieldsName(['name', 'keywords', 'publish'])
            ->setDefaults('publish', 'off')
            ->setMethod('post', [], ['publish']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name', 'publish'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('categories', 'category_name=:name', ['name' => $values['name']])) {
                    $form->setError('این دسته‌بندی وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('categories', [
                    'category_name' => trim($values['name']),
                    'keywords' => trim($values['keywords']),
                    'publish' => $form->isChecked('publish') ? 1 : 0
                ]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['catVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن دسته‌بندی');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        $this->_render_page('pages/be/Category/addCategory');
    }

    public function editCategoryAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('categories', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageCategory'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['catVals'] = $model->select_it(null, 'categories', ['category_name'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editCategory');
        $form->setFieldsName(['name', 'keywords', 'publish'])
            ->setDefaults('status', 'off')
            ->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name', 'publish'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($this->data['catVals']['category_name'] != $values['name']) {
                    if ($model->is_exist('categories', 'category_name=:name', ['name' => $values['name']])) {
                        $form->setError('این دسته‌بندی وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('categories', [
                    'category_name' => trim($values['name']),
                    'keywords' => trim($values['keywords']),
                    'publish' => $form->isChecked('publish') ? 1 : 0
                ], 'id=:id', ['id' => $this->data['param'][0]]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['catVals'] = $form->getValues();
            }
        }

        $this->data['catVals'] = $model->select_it(null, 'categories', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش دسته‌بندی', $this->data['catVals']['category_name']);

        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        $this->_render_page('pages/be/Category/editCategory');
    }

    public function manageCategoryAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['categories'] = $model->select_it(null, 'categories', '*', null, [], null, ['id ASC']);

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده دسته‌بندی‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Category/manageCategory');
    }

    public function deleteCategoryAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'categories';
        if (!isset($id)) {
            message('error', 200, 'دسته‌بندی نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'دسته‌بندی وجود ندارد.');
        }
        if ($model->is_exist($table, 'parent_id=:pId', ['pId' => $id])) {
            message('error', 200, 'این دسته‌بندی شامل زیردسته است.');
        }
        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'دسته‌بندی با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function showInMenuAction()
    {
        if (!$this->auth->isLoggedIn()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $stat = $_POST['stat'];
        $table = 'categories';
        if (!isset($id) || !isset($stat) || !in_array($stat, [0, 1])) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'دسته‌بندی وجود ندارد.');
        }

        $res = $model->update_it($table, ['show_in_menu' => $stat], 'id=:id AND deletable=:del', ['id' => $id, 'del' => 1]);
        if ($res) {
            if ($stat == 1) {
                message('success', 200, 'نمایش دسته‌بندی در منو فعال شد.');
            } else {
                message('warning', 200, 'نمایش دسته‌بندی در منو غیر فعال شد.');
            }
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addBlogAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name']);

        $this->data['errors'] = [];
        $this->data['atcVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addBlog');
        $form->setFieldsName(['title', 'category', 'body', 'publish', 'keywords'])
            ->setDefaults('publish', 'off')
            ->xssOption('body', ['style', 'href', 'src', 'target', 'class'], ['video'])
            ->setMethod('post', [], ['publish']);

        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['title', 'category', 'body'], 'فیلدهای ضروری را خالی نگذارید.');

                if ($model->is_exist('blog', 'title=:title', ['title' => $values['title']])) {
                    $form->setError('این نوشته وجود دارد. لطفا دوباره تلاش کنید.');
                }
                if (!in_array($values['category'], array_column($this->data['categories'], 'id'))) {
                    $form->setError('دسته‌بندی نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('blog', [
                    'title' => trim($values['title']),
                    'slug' => url_title(trim($values['title'])),
                    'body' => $values['body'],
                    'category_id' => $values['category'],
                    'writer' => $this->data['identity']->username,
                    'updater' => null,
                    'keywords' => $values['keywords'],
                    'publish' => $form->isChecked('publish') ? 1 : 0,
                    'created_at' => time(),
                ]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['atcVals'] = $form->getValues();
            }
        }

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن نوشته جدید');

        $this->_render_page([
            'pages/be/Blog/addBlog',
            'templates/be/browser-tiny-func'
        ]);
    }

    public function editBlogAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('blog', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageBlog'));
        }

        $this->data['param'] = $param;

        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name']);

        $this->data['errors'] = [];
        $this->data['atcVals'] = $model->select_it(null, 'blog', ['title'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editBlog');
        $form->setFieldsName(['title', 'category', 'body', 'publish', 'keywords'])
            ->setDefaults('publish', 'off')
            ->xssOption('body', ['style', 'href', 'src', 'target', 'class'], ['video'])
            ->setMethod('post', [], ['publish']);

        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['title', 'category', 'body'], 'فیلدهای ضروری را خالی نگذارید.');

                if ($values['title'] != $this->data['atcVals']['title']) {
                    if ($model->is_exist('blog', 'title=:title', ['title' => $values['title']])) {
                        $form->setError('این نوشته وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
                if (!in_array($values['category'], array_column($this->data['categories'], 'id'))) {
                    $form->setError('دسته‌بندی نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('blog', [
                    'title' => trim($values['title']),
                    'slug' => url_title(trim($values['title'])),
                    'body' => $values['body'],
                    'category_id' => $values['category'],
                    'updater' => $this->data['identity']->username,
                    'keywords' => $values['keywords'],
                    'publish' => $form->isChecked('publish') ? 1 : 0,
                    'updated_at' => time(),
                ], 'id=:id', ['id' => $this->data['param'][0]]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
            }
        }

        $this->data['atcVals'] = $model->select_it(null, 'blog', '*', 'id=:id', ['id' => $param[0]])[0];

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش نوشته');

        $this->_render_page([
            'pages/be/Blog/editBlog',
            'templates/be/browser-tiny-func'
        ]);
    }

    public function manageBlogAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['blog'] = $model->select_it(null, 'blog');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده نوشته‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Blog/manageBlog');
    }

    public function addStaticPageAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['spgVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addStaticPage');
        $form->setFieldsName(['title', 'url_name', 'body'])
            ->xssOption('body', ['style', 'href', 'src', 'target', 'class'], ['video'])
            ->setMethod('post');

        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['title', 'url_name'], 'فیلدهای ضروری را خالی نگذارید.');

                if ($model->is_exist('static_pages', 'url_name=:url_name', ['url_name' => $values['url_name']])) {
                    $form->setError('این آدرس وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {

                $res = $model->insert_it('static_pages', [
                    'title' => $values['title'],
                    'body' => $values['body'],
                    'url_name' => trim($values['url_name'])
                ]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['spgVals'] = $form->getValues();
            }
        }

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن نوشته جدید');

        $this->_render_page([
            'pages/be/StaticPage/addStaticPage',
            'templates/be/browser-tiny-func'
        ]);
    }

    public function editStaticPageAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('static_pages', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageStaticPage'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['spgVals'] = [];

        $this->data['spgVals'] = $model->select_it(null, 'static_pages', ['url_name'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editStaticPage');
        $form->setFieldsName(['title', 'url_name', 'body'])
            ->xssOption('body', ['style', 'href', 'src', 'target', 'class'], ['video'])
            ->setMethod('post');

        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['title', 'url_name'], 'فیلدهای ضروری را خالی نگذارید.');

                if ($this->data['spgVals']['url_name'] != $values['url_name']) {
                    if ($model->is_exist('static_pages', 'url_name=:url_name', ['url_name' => $values['url_name']])) {
                        $form->setError('این آدرس وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {

                $res = $model->update_it('static_pages', [
                    'title' => $values['title'],
                    'body' => $values['body'],
                    'url_name' => trim($values['url_name'])
                ], 'id=:id', ['id' => $this->data['param'][0]]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
            }
        }

        $this->data['spgVals'] = $model->select_it(null, 'static_pages', '*', 'id=:id', ['id' => $param[0]])[0];

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش نوشته');

        $this->_render_page([
            'pages/be/StaticPage/editStaticPage',
            'templates/be/browser-tiny-func'
        ]);
    }

    public function manageStaticPageAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['pages'] = $model->select_it(null, 'static_pages');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده نوشته‌های ثابت');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/StaticPage/manageStaticPage');
    }

    public function deleteStaticPageAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'static_pages';
        if (!isset($id)) {
            message('error', 200, 'نوشته نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'نوشته وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'نوشته با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function manageCommentAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $cmtPr = $model->join_it(null, 'comments AS c', 'blog AS a', [
            'c.id', 'c.name', 'c.blog_id', 'c.mobile', 'c.publish', 'c.created_on', 'a.image', 'a.title',
        ], 'c.blog_id=a.id', null, [], null, null, null, null, true, 'LEFT');
        $this->data['comments'] = $model->join_it($cmtPr, 'users AS u', 'c', [
            'c.id', 'c.name', 'c.blog_id', 'c.mobile', 'c.publish', 'c.created_on', 'c.image', 'c.title', 'u.id AS user_id'
        ], 'c.mobile=u.username', null, [], null, 'c.created_on DESC', null, null, false, 'RIGHT');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مدیریت نظرات');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Comment/manageComment');
    }

    public function viewCommentAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('comments', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageComment'));
        }

        $cmtPr = $model->join_it(null, 'comments AS c', 'products AS p', [
            'c.id', 'c.user_id', 'c.product_id', 'c.status', 'c.comment_date', 'p.product_title', 'p.image', 'p.product_code',
            'c.cons', 'c.pros', 'c.helpful', 'c.useless', 'c.body'
        ], 'c.product_id=p.id', null, [], null, null, null, null, true, 'LEFT');
        $this->data['comment'] = $model->join_it($cmtPr, 'users AS u', 'c', [
            'c.id', 'c.user_id', 'c.product_id', 'c.status', 'c.comment_date', 'c.product_title', 'c.image', 'c.body', 'u.image AS u_image',
            'c.cons', 'c.pros', 'c.helpful', 'c.useless', 'c.product_code', 'u.username', 'u.first_name', 'u.last_name'
        ], 'c.user_id=u.id', 'c.id=:id', ['id' => $param[0]], null, 'c.comment_date DESC', null, null, false, 'RIGHT')[0];

        if ($this->data['comment']['status'] == 0) {
            $model->update_it('comments', ['status' => 1], 'id=:id', ['id' => $param[0]]);
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده نظر');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');

        $this->_render_page('pages/be/Comment/viewComment');
    }

    public function deleteCommentAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'comments';
        if (!isset($id)) {
            message('error', 200, 'نظر نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'نظر وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'نظر با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function acceptCommentAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $table = 'comments';
        if (!isset($id)) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'نظر وجود ندارد.');
        }

        $curStat = $model->select_it(null, $table, 'status', 'id=:id', ['id' => $id])[0]['status'];
        $newStat = $curStat < 2 ? 2 : $curStat;
        $res = $model->update_it($table, ['status' => $newStat], 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'نظر تایید شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function declineCommentAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $table = 'comments';
        if (!isset($id)) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'نظر وجود ندارد.');
        }

        $curStat = $model->select_it(null, $table, 'status', 'id=:id', ['id' => $id])[0]['status'];
        $newStat = $curStat == 2 ? 1 : $curStat;
        $res = $model->update_it($table, ['status' => $newStat], 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'عدم تایید نظر با موفقیت انجام شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addPlanAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['planVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addPlan');
        $form->setFieldsName(['image', 'title', 'capacity', 'total_price', 'base_price', 'min_price', 'start_date', 'end_date', 'description',
            'active_date', 'deactive_date', 'audience', 'place', 'support_phone', 'support_place', 'rules', 'image_gallery', 'video_gallery'])
            ->xssOption('rules', ['style', 'href', 'src', 'target', 'class'], ['video'])
            ->xssOption('description', ['style', 'href', 'src', 'target', 'class'])
            ->setMethod('post');

        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                $values['audience'] = array_filter($values['audience'], function ($val) {
                    return in_array($val, array_keys(EDU_GRADES));
                });

                $form->isRequired(['image', 'title', 'capacity', 'total_price', 'base_price', 'min_price', 'start_date', 'end_date',
                        'active_date', 'deactive_date', 'audience', 'place', 'support_phone', 'description', 'rules']
                    , 'فیلدهای ضروری را خالی نگذارید.');

                // Check plan duplicate
                if ($model->is_exist('plans', 'title=:title', ['title' => trim($values['title'])])) {
                    $form->setError('این طرح وجود دارد. لطفا دوباره تلاش کنید.');
                }
                // Validate main image
                if (!file_exists($values['image'])) {
                    $form->setError('تصویر شاخص نامعتبر است.');
                }
                // Validate capacity
                $form->validate('numeric', 'capacity', 'ظرفیت باید از نوع عدد باشد.')
                    ->isInRange('capacity', 1, PHP_INT_MAX, 'ظرفیت عددی مثبت و بیشتر از ۱ است.');
                // Validate prices
                $form->validate('numeric', 'total_price', 'قیمت کل طرح باید از نوع عدد باشد.');
                $form->validate('numeric', 'base_price', 'قیمت پایه طرح باید از نوع عدد باشد.');
                $form->validate('numeric', 'min_price', 'قیمت پرداخت باید از نوع عدد باشد.');
                if (is_numeric($values['total_price']) && is_numeric($values['min_price']) &&
                    (int)$values['total_price'] < (int)$values['min_price']) {
                    $form->setError('قیمت طرح باید عددی بزرگتر از قیمت پرداخت باشد.');
                }
                // Validate date timestamps
                if (!isValidTimeStamp($values['start_date']) || !isValidTimeStamp($values['end_date']) ||
                    !isValidTimeStamp($values['active_date']) || !isValidTimeStamp($values['deactive_date'])) {
                    $form->setError('زمان(های) وارد شده برای طرح نامعتبر است.');
                } else {
                    if ($values['start_date'] > $values['end_date']) {
                        $form->setError('زمان شروع طرح باید از تاریخ پایان آن کمتر باشد.');
                    }
                    if ($values['active_date'] > $values['deactive_date']) {
                        $form->setError('زمان شروع ثبت نام باید از تاریخ پایان آن کمتر باشد.');
                    }
                }
                // Validate image gallery
                $values['image_gallery'] = array_filter($values['image_gallery'], function ($val) {
                    return file_exists($val) && is_file($val);
                });
                if (!count($values['image_gallery'])) {
                    $form->setError('انتخاب حداقل یک تصویر برای گالری تصاویر اجباری است.');
                }
                // Validate video gallery
                $values['video_gallery'] = array_filter($values['video_gallery'], function ($val) {
                    return file_exists($val) && is_file($val);
                });
                // Validate options structure
                $values['option_group'] = $_POST['option_group'] ?? [];
                $newOpt = [];
                $k = 0;
                if (is_array($values['option_group'])) {
                    foreach ($values['option_group'] as $key => $value) {
                        if (is_array($value)) {
                            if (isset($value['title']) && is_string($value['title']) && is_numeric($value['radio']) &&
                                in_array($value['radio'], [1, 2]) && is_array($value['name']) && is_array($value['price'])) {
                                $newOpt[$k]['title'] = $value['title'];
                                $newOpt[$k]['radio'] = $value['radio'];
                                foreach ($value['name'] as $idx => $name) {
                                    if (isset($value['name'][$idx]) && isset($value['price'][$idx]) &&
                                        !empty($value['name'][$idx])) {
                                        $newOpt[$k]['name'][] = $value['name'][$idx];
                                        $newOpt[$k]['price'][] = !empty($value['price'][$idx])
                                            ? (is_numeric($value['price'][$idx]) && $value['price'][$idx] > 0
                                                ? convertNumbersToPersian($value['price'][$idx], true)
                                                : 0)
                                            : '';
                                        $newOpt[$k]['desc'][] = $value['desc'][$idx] ?? '';
                                    }
                                }
                                // If there is no name values
                                if (!isset($newOpt[$k]['name'])) {
                                    unset($newOpt[$k]);
                                    --$k;
                                }
                                // Default behavior
                                ++$k;
                            }
                        }
                    }
                }
                $values['option_group'] = $newOpt; // Assign new option groups
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $model->transactionBegin();

                $res = $model->insert_it('plans', [
                    'title' => trim($values['title']),
                    'slug' => trim(url_title($values['title'])),
                    'contact' => implode(',', array_map('trim', $values['audience'])),
                    'capacity' => convertNumbersToPersian(trim($values['capacity']), true),
                    'total_price' => convertNumbersToPersian(trim($values['total_price']), true),
                    'base_price' => convertNumbersToPersian(trim($values['base_price']), true),
                    'min_price' => convertNumbersToPersian(trim($values['min_price']), true),
                    'image' => trim($values['image']),
                    'description' => trim($values['description']),
                    'rules' => trim($values['rules']),
                    'start_at' => convertNumbersToPersian($values['start_date'], true),
                    'end_at' => convertNumbersToPersian($values['end_date'], true),
                    'active_at' => convertNumbersToPersian($values['active_date'], true),
                    'deactive_at' => convertNumbersToPersian($values['deactive_date'], true),
                    'support_place' => empty(trim($values['support_place'])) ? null : trim($values['support_place']),
                    'support_phone' => trim($values['support_phone']),
                    'place' => trim($values['place']),
                    'options' => json_encode($values['option_group']),
                    'status' => PLAN_STATUS_DEACTIVATE,
                ], [], true);

                // Insert images to gallery table
                $res2 = false;
                foreach ($values['image_gallery'] as $img) {
                    $res2 = $model->insert_it('plan_images', [
                        'plan_id' => $res,
                        'image' => $img,
                    ]);
                    if (!$res2) break;
                }

                // Insert videos to gallery table
                $res3 = false;
                foreach ($values['video_gallery'] as $video) {
                    $res3 = $model->insert_it('plan_videos', [
                        'plan_id' => $res,
                        'video' => $video,
                    ]);
                    if (!$res3) break;
                }

                if ($res && $res2 && $res3) {
                    $model->transactionComplete();
                } else {
                    $model->transactionRollback();
                    $form->setError('خطا در انجام عملیات!');
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $form->checkForm()->isSuccess();
        if ($form->isSubmit()) {
            if ($res) {
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['planVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن طرح جدید');

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/persian-datepicker-custom.css');
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-date.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-datepicker.min.js');
        $this->data['js'][] = $this->asset->script('be/js/propertyJs.js');
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        $this->_render_page([
            'pages/be/Plan/addPlan',
            'templates/be/browser-tiny-func',
            'templates/be/efm'
        ]);
    }

    public function editPlanAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('plans', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/managePlan'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['planVals'] = $model->select_it(null, 'plans', ['title'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addPlan');
        $form->setFieldsName(['image', 'title', 'capacity', 'total_price', 'base_price', 'min_price', 'start_date', 'end_date', 'description',
            'active_date', 'deactive_date', 'audience', 'place', 'support_phone', 'support_place', 'rules', 'image_gallery', 'video_gallery'])
            ->xssOption('rules', ['style', 'href', 'src', 'target', 'class'], ['video'])
            ->xssOption('description', ['style', 'href', 'src', 'target', 'class'])
            ->setMethod('post');

        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                $values['audience'] = array_filter($values['audience'], function ($val) {
                    return in_array($val, array_keys(EDU_GRADES));
                });

                $form->isRequired(['image', 'title', 'capacity', 'total_price', 'base_price', 'min_price', 'start_date', 'end_date',
                        'active_date', 'deactive_date', 'audience', 'place', 'support_phone', 'description', 'rules']
                    , 'فیلدهای ضروری را خالی نگذارید.');

                // Check plan duplicate
                if ($this->data['planVals']['title'] != $values['title']) {
                    if ($model->is_exist('plans', 'title=:title', ['title' => trim($values['title'])])) {
                        $form->setError('این طرح وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
                // Validate main image
                if (!file_exists($values['image'])) {
                    $form->setError('تصویر شاخص نامعتبر است.');
                }
                // Validate capacity
                $form->validate('numeric', 'capacity', 'ظرفیت باید از نوع عدد باشد.')
                    ->isInRange('capacity', 1, PHP_INT_MAX, 'ظرفیت عددی مثبت و بیشتر از ۱ است.');
                // Validate prices
                $form->validate('numeric', 'total_price', 'قیمت کل طرح باید از نوع عدد باشد.');
                $form->validate('numeric', 'base_price', 'قیمت پایه طرح باید از نوع عدد باشد.');
                $form->validate('numeric', 'min_price', 'قیمت پرداخت باید از نوع عدد باشد.');
                if (is_numeric($values['total_price']) && is_numeric($values['min_price']) &&
                    (int)$values['total_price'] < (int)$values['min_price']) {
                    $form->setError('قیمت طرح باید عددی بزرگتر از قیمت پرداخت باشد.');
                }
                // Validate date timestamps
                if (!isValidTimeStamp($values['start_date']) || !isValidTimeStamp($values['end_date']) ||
                    !isValidTimeStamp($values['active_date']) || !isValidTimeStamp($values['deactive_date'])) {
                    $form->setError('زمان(های) وارد شده برای طرح نامعتبر است.');
                } else {
                    if ($values['start_date'] > $values['end_date']) {
                        $form->setError('زمان شروع طرح باید از تاریخ پایان آن کمتر باشد.');
                    }
                    if ($values['active_date'] > $values['deactive_date']) {
                        $form->setError('زمان شروع ثبت نام باید از تاریخ پایان آن کمتر باشد.');
                    }
                }
                // Validate image gallery
                $values['image_gallery'] = array_filter($values['image_gallery'], function ($val) {
                    return file_exists($val) && is_file($val);
                });
                if (!count($values['image_gallery'])) {
                    $form->setError('انتخاب حداقل یک تصویر برای گالری تصاویر اجباری است.');
                }
                // Validate video gallery
                $values['video_gallery'] = array_filter($values['video_gallery'], function ($val) {
                    return file_exists($val) && is_file($val);
                });
                // Validate options structure
                $values['option_group'] = $_POST['option_group'] ?? [];
                $newOpt = [];
                $k = 0;
                if (is_array($values['option_group'])) {
                    foreach ($values['option_group'] as $key => $value) {
                        if (is_array($value)) {
                            if (isset($value['title']) && is_string($value['title']) && is_numeric($value['radio']) &&
                                in_array($value['radio'], [1, 2]) && is_array($value['name']) && is_array($value['price'])) {
                                $newOpt[$k]['title'] = $value['title'];
                                $newOpt[$k]['radio'] = $value['radio'];
                                foreach ($value['name'] as $idx => $name) {
                                    if (isset($value['name'][$idx]) && isset($value['price'][$idx]) &&
                                        !empty($value['name'][$idx])) {
                                        $newOpt[$k]['name'][] = $value['name'][$idx];
                                        $newOpt[$k]['price'][] = !empty($value['price'][$idx])
                                            ? (is_numeric($value['price'][$idx]) && $value['price'][$idx] > 0
                                                ? convertNumbersToPersian($value['price'][$idx], true)
                                                : 0)
                                            : '';
                                        $newOpt[$k]['desc'][] = $value['desc'][$idx] ?? '';
                                    }
                                }
                                // If there is no name values
                                if (!isset($newOpt[$k]['name'])) {
                                    unset($newOpt[$k]);
                                    --$k;
                                }
                                // Default behavior
                                ++$k;
                            }
                        }
                    }
                }
                $values['option_group'] = $newOpt; // Assign new option groups
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $model->transactionBegin();

                $res = $model->update_it('plans', [
                    'title' => trim($values['title']),
                    'slug' => trim(url_title($values['title'])),
                    'contact' => implode(',', array_map('trim', $values['audience'])),
                    'capacity' => convertNumbersToPersian(trim($values['capacity']), true),
                    'total_price' => convertNumbersToPersian(trim($values['total_price']), true),
                    'base_price' => convertNumbersToPersian(trim($values['base_price']), true),
                    'min_price' => convertNumbersToPersian(trim($values['min_price']), true),
                    'image' => trim($values['image']),
                    'description' => trim($values['description']),
                    'rules' => trim($values['rules']),
                    'start_at' => convertNumbersToPersian($values['start_date'], true),
                    'end_at' => convertNumbersToPersian($values['end_date'], true),
                    'active_at' => convertNumbersToPersian($values['active_date'], true),
                    'deactive_at' => convertNumbersToPersian($values['deactive_date'], true),
                    'support_place' => empty(trim($values['support_place'])) ? null : trim($values['support_place']),
                    'support_phone' => trim($values['support_phone']),
                    'place' => trim($values['place']),
                    'options' => json_encode($values['option_group']),
                ], 'id=:id', ['id' => $this->data['param'][0]]);

                // Delete previous gallery images
                $res3 = $model->delete_it('plan_images', 'plan_id=:pId', ['pId' => $this->data['param'][0]]);
                // Insert images to gallery table
                $res2 = false;
                foreach ($values['image_gallery'] as $img) {
                    $res2 = $model->insert_it('plan_images', [
                        'plan_id' => $this->data['param'][0],
                        'image' => $img,
                    ]);
                    if (!$res2) break;
                }

                // Delete previous gallery images
                $res4 = $model->delete_it('plan_videos', 'plan_id=:pId', ['pId' => $this->data['param'][0]]);
                // Insert images to gallery table
                $res5 = false;
                foreach ($values['video_gallery'] as $video) {
                    $res5 = $model->insert_it('plan_videos', [
                        'plan_id' => $this->data['param'][0],
                        'video' => $video,
                    ]);
                    if (!$res5) break;
                }

                if ($res && $res2 && $res3 && $res4 && $res5) {
                    $model->transactionComplete();
                } else {
                    $model->transactionRollback();
                    $form->setError('خطا در انجام عملیات!');
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $form->checkForm()->isSuccess();
        if ($form->isSubmit()) {
            if ($res) {
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['planVals'] = $form->getValues();
            }
        }

        $this->data['planVals'] = $model->select_it(null, 'plans', '*', 'id=:id', ['id' => $param[0]])[0];
        $this->data['planVals']['options'] = json_decode($this->data['planVals']['options'], true);
        $this->data['planVals']['contact'] = explode(',', $this->data['planVals']['contact']);
        $this->data['planVals']['image_gallery'] = array_column($model->select_it(null, 'plan_images', ['image'], 'plan_id=:pId', ['pId' => $param[0]]), 'image');
        $this->data['planVals']['video_gallery'] = array_column($model->select_it(null, 'plan_videos', ['video'], 'plan_id=:pId', ['pId' => $param[0]]), 'video');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش طرح');

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/persian-datepicker-custom.css');
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-date.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-datepicker.min.js');
        $this->data['js'][] = $this->asset->script('be/js/propertyJs.js');
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        $this->_render_page([
            'pages/be/Plan/editPlan',
            'templates/be/browser-tiny-func',
            'templates/be/efm'
        ]);
    }

    public function managePlanAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['plans'] = $model->select_it(null, 'plans', '*', null, [], null, ['id DESC']);
        foreach ($this->data['plans'] as &$plan) {
            $sub = $model->select_it(null, 'factors', ['COUNT(*)'], 'plan_id=:pId AND payed_amount IS NOT NULL', [],
                ['plan_id'], null, null, null, true);
            $plan['filled'] = $model->it_count($sub, null, ['pId' => $plan['id']], false, true);
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده طرح‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/sliders/nouislider.min.js');
        $this->data['js'][] = $this->asset->script('be/js/planJs.js');

        $this->_render_page('pages/be/Plan/managePlan');
    }

    public function detailPlanAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        if(!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('plans', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/managePlan'));
        }

        $this->data['planVals'] = $model->select_it(null, 'plans', '*',
            'id=:id', ['id' => $param[0]], null, ['id DESC'])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جزئیات طرح‌ها');

        $this->_render_page('pages/be/Plan/detailPlan');
    }

    public function deletePlanAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'plans';
        if (!isset($id)) {
            message('error', 200, 'طرح نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'طرح وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'طرح با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function publishPlanAction()
    {
        if (!$this->auth->isLoggedIn()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $stat = $_POST['stat'];
        $table = 'plans';
        if (!isset($id) || !isset($stat) || !in_array($stat, [0, 1])) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'طرح وجود ندارد.');
        }

        $res = $model->update_it($table, ['publish' => $stat], 'id=:id', ['id' => $id]);
        if ($res) {
            if ($stat == 1) {
                message('success', 200, 'نمایش طرح فعال شد.');
            } else {
                message('warning', 200, 'نمایش طرح غیرفعال شد.');
            }
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function changePlanStatusAction()
    {
        if (!$this->auth->isLoggedIn()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $stat = $_POST['stat'];
        $table = 'plans';
        if (!isset($id) || !isset($stat) || !in_array($stat, [PLAN_STATUS_ACTIVATE, PLAN_STATUS_DEACTIVATE, PLAN_STATUS_FULL, PLAN_STATUS_CLOSED])) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'طرح وجود ندارد.');
        }

        $res = $model->update_it($table, ['status' => $stat], 'id=:id', ['id' => $id]);
        if ($res) {
            if ($stat == PLAN_STATUS_ACTIVATE) {
                message('success', 200, 'وضعیت طرح به فعال تغییر یافت.');
            } elseif ($stat == PLAN_STATUS_DEACTIVATE) {
                message('warning', 200, 'وضعیت طرح به غیر فعال تغییر یافت.');
            } elseif ($stat == PLAN_STATUS_FULL) {
                message('warning', 200, 'وضعیت طرح به پر شده تغییر یافت.');
            } elseif ($stat == PLAN_STATUS_CLOSED) {
                message('warning', 200, 'وضعیت طرح به بسته شده تغییر یافت.');
            }
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function manageFactorAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $factorModel = new FactorModel();
        $this->data['factors'] = $factorModel->getFactors();

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده فاکتورهای ثبت شده');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Factor/manageFactor');
    }

    public function viewFactorAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('factors', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageFactor'));
        }

        // Select all send status labels
        $this->data['allSendStatus'] = $model->select_it(null, 'send_status', '*', null, [], null, 'priority ASC');

        // Select current send_status from factor
        $sendStatus = $model->select_it(null, 'factors', 'send_status', 'id=:id', ['id' => $param[0]])[0]['send_status'];

        // Submit a form for change send status code
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['errors'] = [];
        $this->data['form_token'] = $form->csrfToken('sendStatus');
        $form->setFieldsName(['send-status'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($form, $sendStatus) {
                if (!in_array($values['send-status'], array_column($this->data['allSendStatus'], 'id'))) {
                    $form->setError('وضعیت ارسال انتخاب شده نامعتبر است.');
                }

                if ($sendStatus != $values['send_status']) {
                    // Send SMS to user to notify its factor status has changed

                }
            })->afterCheckCallback(function ($values) use ($form, $model, $param) {
                $res = $model->update_it('factors', [
                    'send_status' => (int)$values['send-status']
                ], 'id=:id', ['id' => $param[0]]);

                if (!$res) {
                    $form->setError('بروزرسانی وضعیت ارسال با خطا مواجه شد.');
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $form->checkForm()->isSuccess();
        if ($form->isSubmit()) {
            if ($res) {
                $this->data['success'] = 'وضعیت ارسال برورسانی شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['statusVals'] = $form->getValues();
            }
        }

        // Select current factor
        $this->data['factor'] = $model->join_it(null, 'factors AS f', 'users AS u', [
            'f.id', 'f.factor_code', 'f.user_id', 'f.first_name', 'f.last_name', 'f.mobile', 'f.method_code', 'f.payment_title', 'f.payment_status',
            'f.send_status', 'f.amount', 'f.shipping_title', 'f.shipping_price', 'f.final_amount', 'f.coupon_title', 'f.coupon_amount', 'f.coupon_unit',
            'f.discount_price', 'f.shipping_address', 'f.shipping_receiver', 'f.shipping_province', 'f.shipping_city', 'f.shipping_postal_code',
            'f.shipping_phone', 'f.want_factor', 'f.payment_date', 'f.shipping_date', 'f.order_date', 'u.id AS u_id'
        ], 'f.user_id=u.id', 'f.id=:id', ['id' => $param[0]],
            null, 'f.id DESC', null, null, false, 'LEFT')[0];
        // Select current factor status label
        $this->data['factorStatus'] = $model->select_it(null, 'send_status', ['name', 'badge'],
            'id=:id', ['id' => $this->data['factor']['send_status']])[0];
        // Select gateway table if gateway code is one of the bank payment gateway's code
        foreach ($this->gatewayTables as $table => $codeArr) {
            if (array_search($this->data['factor']['method_code'], $codeArr) !== false) {
                $gatewayTable = $table;
                break;
            }
        }
        if (isset($gatewayTable)) {
            $successCode = $this->gatewaySuccessCode[$gatewayTable];
            if ($model->is_exist($gatewayTable, 'factor_code=:fc AND status=:s',
                ['fc' => $this->data['factor']['factor_code'], 's' => $successCode])) {
                $this->data['factor']['payment_info'] = $model->select_it(null, $gatewayTable, ['payment_code'],
                    'factor_code=:fc AND status=:s', ['fc' => $this->data['factor']['factor_code'], 's' => $successCode],
                    null, 'payment_date DESC');
            } else {
                $this->data['factor']['payment_info'] = $model->select_it(null, $gatewayTable, ['payment_code'],
                    'factor_code=:fc', ['fc' => $this->data['factor']['factor_code']], null, 'payment_date DESC');
            }
            if (count($this->data['factor']['payment_info'])) {
                $this->data['factor']['payment_info'] = $this->data['factor']['payment_info'][0];
            } else {
                unset($this->data['factor']['payment_info']);
            }
        }
        // Select current factor item(s)
        $this->data['factorItems'] = $model->join_it(null, 'factors_item AS fi', 'products AS p', [
            'fi.product_color', 'fi.product_color_hex', 'fi.product_count', 'fi.product_unit_price', 'fi.product_price',
            'p.product_title', 'p.image', 'p.product_code',
        ], 'fi.product_code=p.product_code', 'fi.factor_code=:fc', ['fc' => $this->data['factor']['factor_code']],
            null, 'fi.id DESC', null, null, false, 'LEFT');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده سفارش');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');

        $this->_render_page('pages/be/Factor/viewFactor');
    }

    public function addUsefulLinkAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['uslVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addUsefulLink');
        $form->setFieldsName(['image', 'title', 'link'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['image', 'title', 'link'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('helpful_links', 'title=:name', ['name' => $values['title']])) {
                    $form->setError('این لینک وجود دارد. لطفا دوباره تلاش کنید.');
                }
                if (!file_exists($values['image'])) {
                    $form->setError('تصویر انتخاب شده، نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('helpful_links', [
                    'image' => trim($values['image']),
                    'title' => trim($values['title']),
                    'link' => trim($values['link']),
                ]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['uslVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن لینک مفید');

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        $this->_render_page([
            'pages/be/UsefulLink/addUsefulLink',
            'templates/be/efm'
        ]);
    }

    public function editUsefulLinkAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('helpful_links', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageUsefulLink'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['uslVals'] = $model->select_it(null, 'helpful_links', ['title'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editBrand');
        $form->setFieldsName(['image', 'title', 'link'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['image', 'title', 'link'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($this->data['uslVals']['title'] != $values['title']) {
                    if ($model->is_exist('helpful_links', 'title=:name', ['name' => $values['title']])) {
                        $form->setError('این لینک وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
                if (!file_exists($values['image'])) {
                    $form->setError('تصویر انتخاب شده، نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('helpful_links', [
                    'image' => trim($values['image']),
                    'title' => trim($values['title']),
                    'link' => trim($values['link']),
                ], 'id=:id', ['id' => $this->data['param'][0]]);

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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
            }
        }

        $this->data['uslVals'] = $model->select_it(null, 'helpful_links', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش لینک مفید', $this->data['uslVals']['title']);

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        $this->_render_page([
            'pages/be/UsefulLink/editUsefulLink',
            'templates/be/efm'
        ]);
    }

    public function manageUsefulLinkAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['links'] = $model->select_it(null, 'helpful_links');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده لینک‌های مفید');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/UsefulLink/manageUsefulLink');
    }

    public function deleteUsefulLinkAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'helpful_links';
        if (!isset($id)) {
            message('error', 200, 'لینک نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'لینک وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'لینک با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function manageFAQAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        // If we are in edit mode
        $editId = isset($param[1]) && strtolower($param[0]) == 'edit' ? (int)$param[1] : 0;
        $editId = $editId && $model->is_exist('faq', 'id=:id', ['id' => $editId]) ? $editId : 0;

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['faqVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('manageFAQ');
        $form->setFieldsName(['answer', 'question'])
            ->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['answer', 'question'], 'فیلدهای ضروری را خالی نگذارید.');
            })->afterCheckCallback(function ($values) use ($model, $form, $editId) {
                $update = $editId ? true : false;
                if ($update) {
                    $res = $model->update_it('faq', [
                        'answer' => trim($values['answer']),
                        'question' => trim($values['question']),
                    ], 'id=:id', ['id' => $editId]);
                } else {
                    $res = $model->insert_it('faq', [
                        'answer' => trim($values['answer']),
                        'question' => trim($values['question']),
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
                $this->data['success'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors'] = $form->getError();
                $this->data['faqVals'] = $form->getValues();
            }
        }

        $this->data['total'] = $model->it_count('faq');
        $this->data['page'] = isset($param[1]) && strtolower($param[0]) == 'page' ? (int)$param[1] : 1;
        $this->data['limit'] = 10;
        $this->data['offset'] = ($this->data['page'] - 1) * $this->data['limit'];
        $this->data['firstPage'] = 1;
        $this->data['lastPage'] = ceil($this->data['total'] / $this->data['limit']);
        $this->data['faqs'] = $model->select_it(null, 'faq', '*', null, [],
            null, ['id DESC'], $this->data['limit'], $this->data['offset']);

        // Get query in edit mode
        if ($editId) {
            $this->data['faqVals'] = $model->select_it(null, 'faq', '*', 'id=:id', ['id' => $editId])[0];
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), $editId ? 'ویرایش' : 'افزودن' . ' سؤال');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');

        $this->_render_page('pages/be/FAQ/manageFAQ');
    }

    public function deleteFAQAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'faq';
        if (!isset($id)) {
            message('error', 200, 'پیام نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'سوال وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'سوال با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function manageContactUsAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['cusVals'] = $model->select_it(null, 'contact_us', '*', null, null, null, ['sent_at DESC']);

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مدیریت تماس با ما');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/ContactUs/manageContactUs');
    }

    public function viewContactAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('contact_us', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageContactUs'));
        }

        $this->data['cusVals'] = $model->select_it(null, 'contact_us', '*', 'id =:id', ['id' => $param[0]])[0];
        if ($this->data['cusVals']['status'] == 0) {
            $model->update_it('contact_us', ['status' => 1], 'id=:id', ['id' => $param[0]]);
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده پیام');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');

        $this->_render_page('pages/be/ContactUs/viewContact');
    }

    public function deleteContactAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'contact_us';
        if (!isset($id)) {
            message('error', 200, 'پیام نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'پیام وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'پیام با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function fileUploadAction($params)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload();
        $this->data['upload']['allow_create_folder'] = allow_create_folder();
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        if (isset($params[0]) && $params[0] == 'download') {
            unset($params[0]);
            $otherParams = implode(DS, $params);
            if ($otherParams != '') {
                $file = str_replace('@', '.', $otherParams);
                if (file_exists($file)) {
                    $filename = basename($file);
                    header('Content-Type: ' . mime_content_type($file));
                    header('Content-Length: ' . filesize($file));
                    header(sprintf('Content-Disposition: attachment; filename=%s',
                        strpos('MSIE', $_SERVER['HTTP_REFERER']) ? rawurlencode($filename) : "\"$filename\""));
                    ob_flush();
                    readfile(base_url($file));
                    exit;
                }
            }
        }

        // Base configuration
        // Extra header information
        $this->data['title'] = titleMaker(' - ', set_value($this->setting['main']['title'] ?? ''), 'پنل مدیریت', 'مدیریت فایل‌ها');

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');
        $this->data['css'][] = $this->asset->css('be/css/treeview.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');

        $this->load->view('templates/be/admin-header-part', $this->data);
        $this->load->view('pages/be/file-upload', $this->data);
        $this->load->view('templates/be/admin-js-part', $this->data);
        $this->load->view('templates/be/efm-main', $this->data);
        $this->load->view('templates/be/admin-footer-part', $this->data);
    }

    public function settingAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        try {
            if (!$this->auth->isAllow('setting', 2)) {
                $this->error->access_denied();
            }
        } catch (HAException $e) {
            echo $e;
        }

        $this->load->library('HForm/Form');

        $this->data['setting'] = [];

        // Main panel setting form submit
        $formMain = new Form();
        $this->data['errors_main'] = [];
        $this->data['form_token_main'] = $formMain->csrfToken('settingMain');
        $formMain->setFieldsName(['fav', 'logo', 'title', 'desc', 'keywords'])->setMethod('post');
        try {
            $formMain->beforeCheckCallback(function ($values) use ($formMain) {
                $formMain->isRequired(['logo', 'title'], 'فیلدهای ضروری را خالی نگذارید.');
                if (!file_exists($values['fav'])) {
                    $formMain->setError('تصویر انتخاب شده برای بالای صفحات، نامعتبر است!');
                }
                if (!file_exists($values['logo'])) {
                    $formMain->setError('تصویر انتخاب شده برای لوگو نامعتبر است!');
                }
            })->afterCheckCallback(function ($values) use ($formMain) {
                $this->data['setting']['main']['favIcon'] = $values['fav'];
                $this->data['setting']['main']['logo'] = $values['logo'];
                $this->data['setting']['main']['title'] = $values['title'];
                $this->data['setting']['main']['description'] = $values['desc'];
                $this->data['setting']['main']['keywords'] = $values['keywords'];

                $this->setting = array_merge_recursive_distinct($this->setting, $this->data['setting']);
                $res = write_json(CORE_PATH . 'config.json', $this->setting);

                if (!$res) {
                    $formMain->setError('خطا در انجام عملیات!');
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $formMain->checkForm()->isSuccess();
        if ($formMain->isSubmit()) {
            if ($res) {
                $this->data['success_main'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors_main'] = $formMain->getError();
            }
        }

        // Images panel setting form submit
        $formImages = new Form();
        $this->data['errors_images'] = [];
        $this->data['form_token_images'] = $formImages->csrfToken('settingImages');
        $formImages->setFieldsName([
            'imgTop', 'showMiddle', 'imgMiddle', 'middleTitle', 'middleDesc'
        ])->setDefaults('showMiddle', 'off')
            ->setMethod('post', [], ['showMiddle']);
        try {
            $formImages->beforeCheckCallback(function (&$values) use ($formImages) {
                if ($values['imgTop'] != '' && !file_exists($values['imgTop'])) {
                    $values['imgTop'] = '';
                }
                if ($values['imgMiddle'] != '' && !file_exists($values['imgMiddle'])) {
                    $values['imgMiddle'] = '';
                }
            })->afterCheckCallback(function ($values) use ($formImages) {
                $props = array_map(function ($val1, $val2) {
                    return ['title' => $val1, 'desc' => $val2];
                }, $values['middleTitle'], $values['middleDesc']);
                //-----
                $this->data['setting']['pages']['index']['topImage']['image'] = $values['imgTop'];
                //-----
                $this->data['setting']['pages']['index']['middlePart']['show'] = $formImages->isChecked('showMiddle') ? 1 : 0;
                $this->data['setting']['pages']['index']['middlePart']['image'] = $values['imgMiddle'];
                $this->data['setting']['pages']['index']['middlePart']['properties'] = $props;

                $this->setting = array_merge_recursive_distinct($this->setting, $this->data['setting']);
                $res = write_json(CORE_PATH . 'config.json', $this->setting);

                if (!$res) {
                    $formImages->setError('خطا در انجام عملیات!');
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $formImages->checkForm()->isSuccess();
        if ($formImages->isSubmit()) {
            if ($res) {
                $this->data['success_images'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors_images'] = $formImages->getError();
            }
        }

        // Others panel setting form submit
        $formImages = new Form();
        $this->data['errors_others'] = [];
        $this->data['form_token_others'] = $formImages->csrfToken('settingOthers');
        $formImages->setFieldsName([
            'otherImgTop',
        ])->setMethod('post');
        try {
            $formImages->beforeCheckCallback(function (&$values) use ($formImages) {
                if ($values['otherImgTop'] != '' && !file_exists($values['otherImgTop'])) {
                    $values['otherImgTop'] = '';
                }
            })->afterCheckCallback(function ($values) use ($formImages) {
                //-----
                $this->data['setting']['pages']['all']['topImage']['image'] = $values['otherImgTop'];
                //-----

                $this->setting = array_merge_recursive_distinct($this->setting, $this->data['setting']);
                $res = write_json(CORE_PATH . 'config.json', $this->setting);

                if (!$res) {
                    $formImages->setError('خطا در انجام عملیات!');
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $formImages->checkForm()->isSuccess();
        if ($formImages->isSubmit()) {
            if ($res) {
                $this->data['success_others'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors_others'] = $formImages->getError();
            }
        }

        // Contact panel setting form submit
        $form = new Form();
        $this->data['errors_contact'] = [];
        $this->data['form_token_contact'] = $form->csrfToken('settingContact');
        $form->setFieldsName([
            'contact-desc', 'contact-mobile',
            'contact-socialEmail', 'contact-telegram', 'contact-instagram', 'contact-facebook',
        ])->setMethod('post');
        try {
            $form->afterCheckCallback(function ($values) use ($form) {
                $this->data['setting']['contact']['description'] = $values['contact-desc'];
                $this->data['setting']['contact']['mobiles'] = $values['contact-mobile'];
                //-----
                $this->data['setting']['contact']['socials']['email'] = $values['contact-socialEmail'];
                $this->data['setting']['contact']['socials']['telegram'] = $values['contact-telegram'];
                $this->data['setting']['contact']['socials']['instagram'] = $values['contact-instagram'];
                $this->data['setting']['contact']['socials']['facebook'] = $values['contact-facebook'];

                $this->setting = array_merge_recursive_distinct($this->setting, $this->data['setting']);
                $res = write_json(CORE_PATH . 'config.json', $this->setting);

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
                $this->data['success_contact'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors_contact'] = $form->getError();
            }
        }

        // Footer panel setting form submit
        $form = new Form();
        $this->data['errors_footer'] = [];
        $this->data['form_token_footer'] = $form->csrfToken('settingFooter');
        $form->setFieldsName([
            'footer_1_title', 'footer_1_text', 'footer_1_link',
            'socialEmail', 'telegram', 'instagram', 'facebook',
        ])->setMethod('post');
        try {
            $form->afterCheckCallback(function ($values) use ($form) {
                $sec1 = array_map(function ($val1, $val2) {
                    return ['text' => $val1, 'link' => $val2];
                }, $values['footer_1_text'][0], $values['footer_1_link'][0]);
                $sec2 = array_map(function ($val1, $val2) {
                    return ['text' => $val1, 'link' => $val2];
                }, $values['footer_1_text'][1], $values['footer_1_link'][1]);
                $sec3 = array_map(function ($val1, $val2) {
                    return ['text' => $val1, 'link' => $val2];
                }, $values['footer_1_text'][2], $values['footer_1_link'][2]);

                $this->data['setting']['footer']['sections']['section_1']['title'] = $values['footer_1_title'][0];
                $this->data['setting']['footer']['sections']['section_1']['links'] = $sec1;

                $this->data['setting']['footer']['sections']['section_2']['title'] = $values['footer_1_title'][1];
                $this->data['setting']['footer']['sections']['section_2']['links'] = $sec2;

                $this->data['setting']['footer']['sections']['section_3']['title'] = $values['footer_1_title'][2];
                $this->data['setting']['footer']['sections']['section_3']['links'] = $sec3;

                $this->data['setting']['footer']['socials']['email'] = $values['socialEmail'];
                $this->data['setting']['footer']['socials']['telegram'] = $values['telegram'];
                $this->data['setting']['footer']['socials']['instagram'] = $values['instagram'];
                $this->data['setting']['footer']['socials']['facebook'] = $values['facebook'];

                $this->setting = array_merge_recursive_distinct($this->setting, $this->data['setting']);
                $res = write_json(CORE_PATH . 'config.json', $this->setting);

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
                $this->data['success_footer'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors_footer'] = $form->getError();
            }
        }

//        $this->data['setting'] = read_json(CORE_PATH . 'config.json');
        $this->data['setting'] = $this->setting;

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        // Base configuration
        // Extra header information
        $this->data['title'] = titleMaker(' - ', set_value($this->setting['main']['title'] ?? ''), 'پنل مدیریت', 'تنظیمات');

        $this->_render_page([
            'templates/be/browser-tiny-func',
            'pages/be/setting',
            'templates/be/efm'
        ]);
    }

    public function easyFileManagerAction()
    {
        if (!$this->auth->isLoggedIn()) {
            err(403, "Forbidden");
        }

        $this->load->helper('easy file manager');
        //Security options
        $allow_delete = allow_delete();
        $allow_upload = allow_upload();
        $allow_create_folder = allow_create_folder();
        $disallowed_extensions = disallowed_extensions();
        $hidden_extensions = hidden_extensions();

        //Disable error report for undefined superglobals
        error_reporting(error_reporting() & ~E_NOTICE);
        // must be in UTF-8 or `basename` doesn't work
        setlocale(LC_ALL, 'en_US.UTF-8');
        $tmp_dir = UPLOAD_PATH;

        if (DIRECTORY_SEPARATOR === '\\') $tmp_dir = str_replace('/', DIRECTORY_SEPARATOR, $tmp_dir);
        $tmp = get_absolute_path($tmp_dir . '/' . $_REQUEST['file']);

        if ($tmp === false)
            err(404, 'File or Directory Not Found');
        if (substr($tmp, 0, strlen($tmp_dir)) !== $tmp_dir)
            err(403, "Forbidden");
        if (strpos($_REQUEST['file'], DIRECTORY_SEPARATOR) === 0)
            err(403, "Forbidden");
        if (!isset($_COOKIE['_sfm_xsrf']))
            setcookie('_sfm_xsrf', bin2hex(openssl_random_pseudo_bytes(16)));
        if ($_POST) {
            if ($_COOKIE['_sfm_xsrf'] !== $_POST['xsrf'] || !$_POST['xsrf'])
                err(403, "XSRF Failure");
        }

        $file = $_REQUEST['file'] ?: UPLOAD_PATH;
        if (strpos(str_replace('\\', '/', $file), str_replace('\\', '/', UPLOAD_PATH)) === false) {
            $file = UPLOAD_PATH;
        }
        $file = str_replace('\\', '/', $file);
        $file = str_replace('//', '/', $file);
        $file = rtrim($file, '/');

        if (isset($_GET['do']) && $_GET['do'] == 'list') {
            if (is_dir($file)) {
                $directory = $file;
                $result = [];
                $files = array_diff(scandir($directory), ['.', '..']);
                foreach ($files as $entry) {
                    $fileExt = get_extension($entry);
                    if ($entry !== basename(__FILE__) && !in_array($fileExt, $hidden_extensions)) {
                        $i = $directory . '/' . $entry;
                        $stat = stat($i);
                        $result[] = [
                            'test' => $directory,
                            'mtime' => $stat['mtime'],
                            'size' => $stat['size'],
                            'ext' => $fileExt,
                            'name' => basename($i),
                            'path' => preg_replace('@^\./@', '', $i),
                            'is_dir' => is_dir($i),
                            'is_deleteable' => $allow_delete && ((!is_dir($i) && is_writable($directory)) ||
                                    (is_dir($i) && is_writable($directory) && is_recursively_deleteable($i))),
                            'is_readable' => is_readable($i),
                            'is_writable' => is_writable($i),
                            'is_executable' => is_executable($i),
                        ];
                    }
                }
            } else {
                err(412, "Not a Directory");
            }
            echo json_encode(['success' => true, 'is_writable' => is_writable($file), 'results' => $result]);
            exit;
        } elseif (isset($_POST['do']) && $_POST['do'] == 'delete') {
            if ($allow_delete) {
                rmrf($file);
            }
            exit;
        } elseif (isset($_POST['do']) && $_POST['do'] == 'mkdir' && $allow_create_folder) {
            // don't allow actions outside root. we also filter out slashes to catch args like './../outside'
            $dir = $_POST['name'];
            $dir = str_replace('/', '', $dir);

            if (check_file_uploaded_length($dir)) {
                err(403, "Invalid name size.");
            }
            if (substr($dir, 0, 2) === '..')
                exit;
            chdir($file);
            @mkdir(str_replace(' ', '-', $_POST['name']));
            exit;
        } elseif (isset($_POST['do']) && $_POST['do'] == 'upload' && $allow_upload) {
            foreach ($disallowed_extensions as $ext) {
                if (preg_match(sprintf('/\.%s$/', preg_quote($ext)), $_FILES['file_data']['name'])) {
                    err(403, "Files of this type are not allowed.");
                }
            }

            $path = $_FILES['file_data']['name'];
            $ext = get_extension($path);

            $this->load->library('XSS/vendor/autoload');

            $xss = new AntiXSS();
            $filename = $xss->xss_clean(str_replace(' ', '-', $_FILES['file_data']['name']));
            $filename = str_replace('@', '', $filename);

            if (check_file_uploaded_length($filename)) {
                err(403, "Invalid name size.");
            }

            var_dump(move_uploaded_file($_FILES['file_data']['tmp_name'], $file . '/' . $filename));
            exit;
        } elseif (isset($_POST['do']) && $_POST['do'] == 'mvdir' && $allow_create_folder) {
            $fileArr = json_decode($_REQUEST['file']);
            foreach ($fileArr as $files) {
                $file = $files;
                $newDir = $_POST['newPath'];

                if (!file_exists($file)) {
                    err(403, "File doesn't exists!");
                }

                if (strpos(str_replace('\\', '/', $file), str_replace('\\', '/', UPLOAD_PATH)) === false
                    || strpos(str_replace('\\', '/', $newDir), str_replace('\\', '/', UPLOAD_PATH)) === false
                ) {
                    err(403, "Invalid folder selected");
                }
                // don't allow actions outside root. we also filter out slashes to catch args like './../outside'
                $dir = str_replace('/', '', $newDir);
                if (substr($dir, 0, 2) === '..')
                    exit;

                $bName = get_base_name($file);
                $newFile = $newDir . '/' . $bName;

                if ($file == $newFile)
                    exit;

                rename($file, $newFile);
            }
            exit;
        }
    }

    public function foldersTreeAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            $this->redirect(base_url('admin/login'));
        }

        $this->load->helper('easy file manager');

        //Disable error report for undefined superglobals
        error_reporting(error_reporting() & ~E_NOTICE);
        // must be in UTF-8 or `basename` doesn't work
        setlocale(LC_ALL, 'en_US.UTF-8');
        $tmp_dir = UPLOAD_PATH;

        if (DIRECTORY_SEPARATOR === '\\') $tmp_dir = str_replace('/', DIRECTORY_SEPARATOR, $tmp_dir);
        $tmp = get_absolute_path($tmp_dir . '/' . $_REQUEST['file']);

        if ($tmp === false)
            err(404, 'File or Directory Not Found');
        if (substr($tmp, 0, strlen($tmp_dir)) !== $tmp_dir)
            err(403, "Forbidden");
        if (strpos($_REQUEST['file'], DIRECTORY_SEPARATOR) === 0)
            err(403, "Forbidden");
        if (!$_COOKIE['_sfm_xsrf'])
            setcookie('_sfm_xsrf', bin2hex(openssl_random_pseudo_bytes(16)));
        if ($_POST) {
            if ($_COOKIE['_sfm_xsrf'] !== $_POST['xsrf'] || !$_POST['xsrf'])
                err(403, "XSRF Failure");
        }

        $file = $_REQUEST['file'];
        if (!$file) {
            err(412, "Not a Directory");
        }

        if (strpos(str_replace('\\', '/', $file), str_replace('\\', '/', UPLOAD_PATH)) === false) {
            err(412, "Not a Directory");
        }

        if (is_dir($file)) {
            $directory = $file;
            $result = [];
            $files = array_diff(scandir($directory), ['.', '..']);
            foreach ($files as $entry) {
                $i = $directory . '/' . $entry;

                if ($entry !== basename(__FILE__) && is_dir($i)) {
                    $result[] = [
                        'name' => basename($i),
                        'path' => preg_replace('@^\./@', '', $i),
                    ];
                }
            }
        } else {
            err(412, "Not a Directory");
        }
        echo json_encode(['success' => true, 'is_writable' => is_writable($file), 'results' => $result]);
        exit;
    }

    public function browserAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->error->access_denied();
        }

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        $this->load->view('pages/be/browser', $this->data);
    }

    private function _render_page($pages, $loadHeaderAndFooter = true)
    {
        if ($loadHeaderAndFooter) {
            $this->load->view('templates/be/admin-header-part', $this->data);
            $this->load->view('templates/be/admin-js-part', $this->data);
        }

        $allPages = is_string($pages) ? [$pages] : (is_array($pages) ? $pages : []);
        foreach ($allPages as $page) {
            $this->load->view($page, $this->data);
        }

        if ($loadHeaderAndFooter) {
            $this->load->view('templates/be/admin-footer-part', $this->data);
        }
    }
}
