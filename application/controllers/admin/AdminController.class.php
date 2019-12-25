<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

use HAuthentication\Auth;
use HAuthentication\HAException;
use HForm\Form;
use HPayment\Payment;
use voku\helper\AntiXSS;

class AdminController extends HController
{
    private $auth;
    private $data = [];
    private $setting;

    // Offer save reaction parameters
    const OFFER_REACTION_SHOW_ERROR = 1;
    const OFFER_REACTION_DISABLE_OTHERS = 2;
    protected $offerReactionParams = [
        self::OFFER_REACTION_SHOW_ERROR,
        self::OFFER_REACTION_DISABLE_OTHERS,
    ];

    // Offer activation change to active reaction parameters
    const OFFER_ACTIVATION_REACTION_SHOW_ERROR = 1;
    const OFFER_ACTIVATION_REACTION_DEACTIVATE_OTHERS = 2;
    const OFFER_ACTIVATION_REACTION_DELETE_OTHER_PRODUCTS = 3;
    protected $offerActivationReactionParams = [
        self::OFFER_ACTIVATION_REACTION_SHOW_ERROR,
        self::OFFER_ACTIVATION_REACTION_DEACTIVATE_OTHERS,
        self::OFFER_ACTIVATION_REACTION_DELETE_OTHER_PRODUCTS
    ];

    // Define payment tables and codes and functions
    const PAYMENT_TABLE_MABNA = 'gateway_mabna';
    const PAYMENT_TABLE_IDPAY = 'gateway_idpay';
    const PAYMENT_TABLE_ZARINPAL = 'gateway_zarinpal';
    protected $gatewayTables = [
        self::PAYMENT_TABLE_IDPAY => [
            'PAY_74214735',
            'PAY_32879731',
        ],
        self::PAYMENT_TABLE_MABNA => [],
        self::PAYMENT_TABLE_ZARINPAL => [],
    ];
    protected $gatewaySuccessCode;

    public function __construct()
    {
        parent::__construct();

        $model = new Model();

        // Define gateway table to its success code after load library to access Payment constants
        $this->load->library('HPayment/vendor/autoload');
        $this->gatewaySuccessCode = [
            self::PAYMENT_TABLE_IDPAY => Payment::PAYMENT_STATUS_OK_IDPAY,
            self::PAYMENT_TABLE_MABNA => Payment::PAYMENT_STATUS_OK_MABNA,
            self::PAYMENT_TABLE_ZARINPAL => Payment::PAYMENT_STATUS_OK_ZARINPAL,
        ];

        $this->load->library('HAuthentication/Auth');
        try {
            $this->auth = new Auth();
            $this->auth->setNamespace('adminPanel')->setExpiration(365 * 24 * 60 * 60);
            $_SESSION['admin_panel_namespace'] = 'hva_rhm_ms_7472';
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

        // All active festivals
        $this->data['activeFestivals'] = $model->select_it(null, 'festivals', [
            'id', 'festival_code', 'festival_title', 'festival_set_date', 'festival_expire_date'
        ], 'status=:s AND festival_set_date<=:fsd AND festival_expire_date>=:fed',
            ['s' => 1, 'fsd' => time(), 'fed' => time()], null, 'id ASC');
        $this->data['activeFestivals'] = count($this->data['activeFestivals']) ? $this->data['activeFestivals'] : [0 => ['id' => -1]];
        $this->data['activeFestivalsId'] = array_column($this->data['activeFestivals'], 'id');

//        $model = new Model();
//        $model->insert_it('users', [
//            'username' => 'godroham@gmail.com',
//            'password' => password_hash('m9516271', PASSWORD_BCRYPT),
//            'ip_address' => get_client_ip_server(),
//            'email' => 'saeedgerami72@gmail.com',
//            'created_on' => time(),
//            'active' => '1',
//            'first_name' => 'سعید',
//            'last_name' => 'گرامی فر',
//            'image' => 'fd4840b2.svg',
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
                $login = $this->auth->login($values['username'], $values['password'], $form->isChecked('remember'), true);
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
        // Brands count
        $this->data['brandsCount'] = $model->it_count('brands');
        // Shippings count
        $this->data['shippingCount'] = $model->it_count('shippings');
        // Payment methods count
        $this->data['payMethodsCount'] = $model->it_count('payment_methods');
        // Coupons count
        $this->data['couponsCount'] = $model->it_count('coupons');
        // Colors count
        $this->data['colorsCount'] = $model->it_count('colors');
        // Factors count
        $this->data['factorsCount'] = $model->it_count('factors');
        // Categories count
        $this->data['catsCount'] = $model->it_count('categories');
        // Products count
        $this->data['productsCount'] = $model->it_count('products');
        // Festivals count
        $this->data['fetivalsCount'] = $model->it_count('festivals');
        // Comments count
        $this->data['commentsCount'] = $model->it_count('comments');

        $this->_render_page('pages/be/index');
    }

    public function editUserAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        if ($this->data['identity']->id != $param[0]) {
            try {
                if (!$this->auth->isAllow('user', 3)) {
                    $this->error->access_denied();
                }
            } catch (HAException $e) {
                echo $e;
            }
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('users', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageUser'));
        }
        $uRole = $model->select_it(null, 'users_roles', 'role_id', 'user_id=:uId', ['uId' => $param[0]]);
        if(count($uRole) && $uRole[0]['role_id'] <= $this->data['identity']->role_id) {
            $this->error->access_denied();
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['userVals'] = [];

        $this->data['userVals'] = $model->select_it(null, 'users', '*', 'id=:id', ['id' => $param[0]])[0];
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

        $this->data['userVals'] = $model->join_it(null, 'users AS u', 'users_roles AS ur', [
            'u.id', 'u.username', 'CONCAT(u.first_name, " ", u.last_name) AS full_name', 'ur.role_id'
        ], 'u.id=ur.user_id', 'u.id=:id', ['id' => $param[0]]);
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
            ['u.id', 'u.username', 'CONCAT(u.first_name, " ", u.last_name) AS full_name', 'u.created_on', 'u.active'], 'u.id=r.user_id',
            'r.role_id>:id AND u.id!=:curId', ['id' => $this->data['identity']->role_id, 'curId' => $this->data['identity']->id],
        null, 'u.id DESC', null, null, false, 'LEFT');

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
        if(!count($uRole) || $uRole[0]['role_id'] > $this->data['identity']->role_id) {
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

    public function addBrandAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['brandVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addBrand');
        $form->setFieldsName(['image', 'brand', 'keywords', 'desc'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['brand', 'image'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('brands', 'brand_name=:name', ['name' => $values['brand']])) {
                    $form->setError('این برند وجود دارد. لطفا دوباره تلاش کنید.');
                }
                if (!file_exists($values['image'])) {
                    $form->setError('تصویر انتخاب شده، نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $common = new CommonModel();
                $code = $common->generate_random_unique_code('brands', 'brand_code', 'BRD_', 8, 15, 10, CommonModel::DIGITS);

                if (empty($code)) {
                    $form->setError('مشکل در ایجاد کد اختصاصی پیش آمده است. لطفا مجددا تلاش نمایید!');
                    return;
                }

                $code = 'BRD_' . $code;

                $res = $model->insert_it('brands', [
                    'brand_code' => $code,
                    'brand_name' => trim($values['brand']),
                    'image' => trim($values['image']),
                    'description' => trim($values['desc']),
                    'keywords' => trim($values['keywords'])
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
                $this->data['brandVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن برند');

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
            'pages/be/Brand/addBrand',
            'templates/be/efm'
        ]);
    }

    public function editBrandAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('brands', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageBrand'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['brandVals'] = [];

        $this->data['brandVals'] = $model->select_it(null, 'brands', ['brand_name'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editBrand');
        $form->setFieldsName(['image', 'brand', 'keywords', 'desc'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['brand', 'image'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($this->data['brandVals']['brand_name'] != $values['brand']) {
                    if ($model->is_exist('brands', 'brand_name=:name', ['name' => $values['brand']])) {
                        $form->setError('این برند وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
                if (!file_exists($values['image'])) {
                    $form->setError('تصویر انتخاب شده، نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('brands', [
                    'brand_name' => trim($values['brand']),
                    'image' => trim($values['image']),
                    'description' => trim($values['desc']),
                    'keywords' => trim($values['keywords']),
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

        $this->data['brandVals'] = $model->select_it(null, 'brands', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش برند', $this->data['brandVals']['brand_name']);

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
            'pages/be/Brand/editBrand',
            'templates/be/efm'
        ]);
    }

    public function manageBrandAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['brands'] = $model->select_it(null, 'brands');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده برندها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Brand/manageBrand');
    }

    public function deleteBrandAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'brands';
        if (!isset($id)) {
            message('error', 200, 'برند نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'برند وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'برند با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function showBrandInPagesAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $stat = $_POST['stat'];
        $table = 'brands';
        if (!isset($id) || !isset($stat) || !in_array($stat, [0, 1])) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'برند وجود ندارد.');
        }

        $res = $model->update_it($table, ['show_in_pages' => $stat], 'id=:id', ['id' => $id]);
        if ($res) {
            if ($stat == 1) {
                message('success', 200, 'نمایش برای برند فعال شد.');
            } else {
                message('warning', 200, 'نمایش برای برند غیر فعال شد.');
            }
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addShippingAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['shVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addShipping');
        $form->setFieldsName(['title', 'price', 'max-price', 'min-days', 'max-days', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                $values['price'] = convertNumbersToPersian($values['price'], true);
                $values['max-price'] = convertNumbersToPersian($values['max-price'], true);
                $values['min-days'] = convertNumbersToPersian($values['min-days'], true);
                $values['max-days'] = convertNumbersToPersian($values['max-days'], true);

                $form->isRequired(['title', 'price'], 'فیلدهای ضروری را خالی نگذارید.');
                $form->validate('numeric', 'price', 'قیمت باید عدد باشد.');
                if (!empty($values['max-price'])) {
                    $form->validate('numeric', 'max-price', 'سقف هزینه باید عدد باشد.');
                }
                if (!empty($values['min-days'])) {
                    $form->validate('numeric', 'min-days', 'حداقل تعداد روز باید عدد باشد.');
                }
                if (!empty($values['max-days'])) {
                    $form->validate('numeric', 'max-days', 'حداکثر تعداد روز باید عدد باشد.');
                }
                if ($model->is_exist('shippings', 'shipping_title=:name', ['name' => $values['title']])) {
                    $form->setError('این روش ارسال وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $common = new CommonModel();
                $code = $common->generate_random_unique_code('shippings', 'shipping_code', 'SHP_', 8, 15, 10, CommonModel::DIGITS);

                if (empty($code)) {
                    $form->setError('مشکل در ایجاد کد اختصاصی پیش آمده است. لطفا مجددا تلاش نمایید!');
                    return;
                }

                $code = 'SHP_' . $code;

                $res = $model->insert_it('shippings', [
                    'shipping_code' => $code,
                    'shipping_title' => trim($values['title']),
                    'shipping_price' => trim($values['price']),
                    'max_price' => trim($values['max-price']),
                    'min_days' => trim($values['min-days']),
                    'max_days' => trim($values['max-days']),
                    'status' => $form->isChecked('status') ? 1 : 0,
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
                $this->data['shVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن شیوه ارسال');

        $this->_render_page('pages/be/Shipping/addShipping');
    }

    public function editShippingAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('shippings', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageShipping'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['shVals'] = [];

        $this->data['shVals'] = $model->select_it(null, 'shippings', ['shipping_title'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editShipping');
        $form->setFieldsName(['title', 'price', 'max-price', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                $values['price'] = convertNumbersToPersian($values['price'], true);
                $values['max-price'] = convertNumbersToPersian($values['max-price'], true);
                $values['min-days'] = convertNumbersToPersian($values['min-days'], true);
                $values['max-days'] = convertNumbersToPersian($values['max-days'], true);

                $form->isRequired(['title', 'price'], 'فیلدهای ضروری را خالی نگذارید.');
                $form->validate('numeric', 'price', 'قیمت باید عدد باشد.');
                if (!empty($values['max-price'])) {
                    $form->validate('numeric', 'max-price', 'سقف هزینه باید عدد باشد.');
                }
                if (!empty($values['min-days'])) {
                    $form->validate('numeric', 'min-days', 'حداقل تعداد روز باید عدد باشد.');
                }
                if (!empty($values['max-days'])) {
                    $form->validate('numeric', 'max-days', 'حداکثر تعداد روز باید عدد باشد.');
                }
                if ($this->data['shVals']['shipping_title'] != $values['title']) {
                    if ($model->is_exist('shippings', 'shipping_title=:name', ['name' => $values['title']])) {
                        $form->setError('این شیوه ارسال وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('shippings', [
                    'shipping_title' => trim($values['title']),
                    'shipping_price' => trim($values['price']),
                    'max_price' => trim($values['max-price']),
                    'min_days' => trim($values['min-days']),
                    'max_days' => trim($values['max-days']),
                    'status' => $form->isChecked('status') ? 1 : 0,
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

        $this->data['shVals'] = $model->select_it(null, 'shippings', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش شیوه ارسال', $this->data['shVals']['shipping_title']);

        $this->_render_page('pages/be/Shipping/editShipping');
    }

    public function manageShippingAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['shippings'] = $model->select_it(null, 'shippings');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده شیوه‌های ارسال');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Shipping/manageShipping');
    }

    public function deleteShippingAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'shippings';
        if (!isset($id)) {
            message('error', 200, 'شیوه ارسال نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'شیوه ارسال وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'شیوه ارسال با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addPaymentAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['payVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addPayment');
        $form->setFieldsName(['title', 'image', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['title'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('payment_methods', 'method_title=:name', ['name' => $values['title']])) {
                    $form->setError('این روش پرداخت وجود دارد. لطفا دوباره تلاش کنید.');
                }
                if ($values['image'] != '' && !file_exists($values['image'])) {
                    $form->setError('تصویر انتخاب شده، نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $common = new CommonModel();
                $code = $common->generate_random_unique_code('payment_methods', 'method_code', 'PAY_', 8, 15, 10, CommonModel::DIGITS);

                if (empty($code)) {
                    $form->setError('مشکل در ایجاد کد اختصاصی پیش آمده است. لطفا مجددا تلاش نمایید!');
                    return;
                }

                $code = 'PAY_' . $code;

                $res = $model->insert_it('payment_methods', [
                    'method_code' => $code,
                    'method_title' => trim($values['title']),
                    'image' => $values['image'],
                    'status' => $form->isChecked('status') ? 1 : 0,
                    'deletable' => 1,
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
                $this->data['payVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن روش پرداخت');

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        $this->_render_page([
            'pages/be/Payment-method/addPayment',
            'templates/be/efm'
        ]);
    }

    public function editPaymentAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('payment_methods', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/managePayment'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['payVals'] = [];

        $this->data['payVals'] = $model->select_it(null, 'payment_methods', ['method_title'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editPayment');
        $form->setFieldsName(['title', 'image', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['title'], 'فیلدهای ضروری را خالی نگذارید.');

                if ($this->data['payVals']['method_title'] != $values['title']) {
                    if ($model->is_exist('payment_methods', 'method_title=:name', ['name' => $values['title']])) {
                        $form->setError('این روش پرداخت وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
                if ($values['image'] != '' && !file_exists($values['image'])) {
                    $form->setError('تصویر انتخاب شده، نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('payment_methods', [
                    'method_title' => trim($values['title']),
                    'image' => $values['image'],
                    'status' => $form->isChecked('status') ? 1 : 0,
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

        $this->data['payVals'] = $model->select_it(null, 'payment_methods', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش روش پرداخت', $this->data['payVals']['method_title']);

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        $this->_render_page([
            'pages/be/Payment-method/editPayment',
            'templates/be/efm'
        ]);
    }

    public function managePaymentAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['payments'] = $model->select_it(null, 'payment_methods');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده روش‌های پرداخت');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Payment-method/managePayment');
    }

    public function deletePaymentAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'payment_methods';
        if (!isset($id)) {
            message('error', 200, 'روش پرداخت نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'روش پرداخت وجود ندارد.');
        }
        if ($model->select_it(null, $table, ['deletable'], 'id=:id', ['id' => $id])[0]['deletable'] != 1) {
            message('error', 200, 'اجازه حذف برای این روش پرداخت داده نشد.');
        }

        $res = $model->delete_it($table, 'id=:id AND deletable=:del', ['id' => $id, 'del' => 1]);
        if ($res) {
            message('success', 200, 'روش پرداخت با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addCouponAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['coVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addCoupon');
        $form->setFieldsName(['code', 'title', 'amount', 'min-price', 'max-price', 'expire', 'unit', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['code', 'title', 'amount', 'min-price', 'expire', 'unit'], 'فیلدهای ضروری را خالی نگذارید.');
                $form->validate('numeric', 'amount', 'تخفیف باید عدد باشد.')
                    ->isLengthInRange('code', 6, 20, 'کد کوپن باید بین ۶ تا ۲۰ کاراکتر باشد.')
                    ->validate('numeric', 'min-price', 'حداقل قیمت اعمال تخفیف باید عدد باشد.')
                    ->isIn('unit', [1, 2], 'واحد انتخاب شده نادرست است.');

                if (!count($form->getError())) {
                    // Check for percentage unit
                    if ($values['unit'] == 2 && ($values['amount'] < 0 || $values['amount'] > 100)) {
                        $form->setError('درصد باید عددی بین ۰ و ۱۰۰ باشد.');
                    }

                    // Check for amount and min-price
                    if ($values['amount'] > $values['min-price']) {
                        $form->setError('حداقل قمت اعمال تخفیف باید از مبلغ تخفیف بیشتر باشد.');
                    }
                }

                if (!isValidTimeStamp($values['expire'])) {
                    $form->setError('تاریخ نامعتبر است.');
                }
                $form->validateDate('expire', date('Y-m-d', $values['expire']), 'تاریخ نامعتبر است.', 'Y-m-d');

                if (!empty($values['max-price'])) {
                    $form->validate('numeric', 'max-price', 'حداکثر قیمت اعمال تخفیف باید عدد باشد.');
                }
                if ($model->is_exist('coupons', 'coupon_title=:name', ['name' => $values['title']])) {
                    $form->setError('این کوپن تخفیف وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $values['code'] = convertNumbersToPersian($values['code'], true);
                $res = $model->insert_it('coupons', [
                    'coupon_code' => trim($values['code']),
                    'coupon_title' => trim($values['title']),
                    'min_price' => convertNumbersToPersian(trim($values['min-price']), true),
                    'max_price' => convertNumbersToPersian(trim($values['max-price']), true),
                    'amount' => convertNumbersToPersian(trim($values['amount']), true),
                    'unit' => trim($values['unit']),
                    'coupon_expire_time' => trim($values['expire']),
                    'status' => $form->isChecked('status') ? 1 : 0,
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
                $this->data['coVals'] = $form->getValues();
            }
        }

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/persian-datepicker-custom.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-date.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-datepicker.min.js');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن کوپن تخفیف');

        $this->_render_page('pages/be/Coupon/addCoupon');
    }

    public function editCouponAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('coupons', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageCoupon'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['coVals'] = [];

        $this->data['coVals'] = $model->select_it(null, 'coupons', ['coupon_title'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editCoupon');
        $form->setFieldsName(['title', 'amount', 'min-price', 'max-price', 'expire', 'unit', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['title', 'amount', 'min-price', 'expire', 'unit'], 'فیلدهای ضروری را خالی نگذارید.');
                $form->validate('numeric', 'amount', 'تخفیف باید عدد باشد.')
                    ->validate('numeric', 'min-price', 'حداقل قیمت اعمال تخفیف باید عدد باشد.')
                    ->isIn('unit', [1, 2], 'واحد انتخاب شده نادرست است.');

                if (!count($form->getError())) {
                    // Check for percentage unit
                    if ($values['unit'] == 2 && ($values['amount'] < 0 || $values['amount'] > 100)) {
                        $form->setError('درصد باید عددی بین ۰ و ۱۰۰ باشد.');
                    }

                    // Check for amount and min-price
                    if ($values['amount'] > $values['min-price']) {
                        $form->setError('حداقل قمت اعمال تخفیف باید از مبلغ تخفیف بیشتر باشد.');
                    }
                }

                if (!isValidTimeStamp($values['expire'])) {
                    $form->setError('تاریخ نامعتبر است.');
                }
                $form->validateDate('expire', date('Y-m-d', $values['expire']), 'تاریخ نامعتبر است.', 'Y-m-d');

                if (!empty($values['max-price'])) {
                    $form->validate('numeric', 'max-price', 'حداکثر قیمت اعمال تخفیف باید عدد باشد.');
                }
                if ($this->data['coVals']['coupon_title'] != $values['title']) {
                    if ($model->is_exist('coupons', 'coupon_title=:name', ['name' => $values['title']])) {
                        $form->setError('این کوپن تخفیف وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('coupons', [
                    'coupon_title' => trim($values['title']),
                    'min_price' => convertNumbersToPersian(trim($values['min-price']), true),
                    'max_price' => convertNumbersToPersian(trim($values['max-price']), true),
                    'amount' => convertNumbersToPersian(trim($values['amount']), true),
                    'unit' => trim($values['unit']),
                    'coupon_expire_time' => trim($values['expire']),
                    'status' => $form->isChecked('status') ? 1 : 0,
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

        $this->data['coVals'] = $model->select_it(null, 'coupons', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش کوپن تخفیف', $this->data['coVals']['coupon_title']);


        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/persian-datepicker-custom.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-date.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-datepicker.min.js');

        $this->_render_page('pages/be/Coupon/editCoupon');
    }

    public function manageCouponAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['coupons'] = $model->select_it(null, 'coupons');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده کوپن‌های تخفیف');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Coupon/manageCoupon');
    }

    public function deleteCouponAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'coupons';
        if (!isset($id)) {
            message('error', 200, 'کوپن تخفیف نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'کوپن تخفیف وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'کوپن تخفیف با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addColorAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['colVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addColor');
        $form->setFieldsName(['name', 'hex'])
            ->setMethod('post');

        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name', 'hex'], 'فیلدهای ضروری را خالی نگذارید.');
                if (!isValidHexColor($values['hex'])) {
                    $form->setError('رنگ نامعتبر است.');
                }

                if ($model->is_exist('colors', 'color_name=:name', ['name' => $values['name']])) {
                    $form->setError('این عنوان رنگ وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $common = new CommonModel();
                $code = $common->generate_random_unique_code('colors', 'color_code', 'COL_');

                if (empty($code)) {
                    $form->setError('مشکل در ایجاد کد اختصاصی پیش آمده است. لطفا مجددا تلاش نمایید!');
                    return;
                }

                $code = 'COL_' . $code;

                $res = $model->insert_it('colors', [
                    'color_code' => $code,
                    'color_name' => trim($values['name']),
                    'color_hex' => trim($values['hex']),
                    'deletable' => 1
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
                $this->data['colVals'] = $form->getValues();
            }
        }

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/persian-datepicker-custom.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-date.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-datepicker.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/spectrum.js');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن رنگ چدید');

        $this->_render_page('pages/be/Color/addColor');
    }

    public function editColorAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('colors', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageColor'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['colVals'] = [];

        $this->data['colVals'] = $model->select_it(null, 'colors', ['color_name'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editColor');
        $form->setFieldsName(['name', 'hex'])
            ->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name', 'hex'], 'فیلدهای ضروری را خالی نگذارید.');
                if (!isValidHexColor($values['hex'])) {
                    $form->setError('رنگ نامعتبر است.');
                }

                if ($this->data['colVals']['color_name'] != $values['name']) {
                    if ($model->is_exist('colors', 'color_name=:name', ['name' => $values['name']])) {
                        $form->setError('این عنوان رنگ وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('colors', [
                    'color_name' => trim($values['name']),
                    'color_hex' => trim($values['hex']),
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
                $this->data['colVals'] = $form->getValues();
            }
        }

        $this->data['colVals'] = $model->select_it(null, 'colors', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش رنگ');

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/persian-datepicker-custom.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-date.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-datepicker.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/spectrum.js');

        $this->_render_page('pages/be/Color/editColor');
    }

    public function manageColorAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['colors'] = $model->select_it(null, 'colors');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده رنگ‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Color/manageColor');
    }

    public function deleteColorAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'colors';
        if (!isset($id)) {
            message('error', 200, 'رنگ نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'رنگ وجود ندارد.');
        }
        if ($model->select_it(null, $table, ['deletable'], 'id=:id', ['id' => $id])[0]['deletable'] != 1) {
            message('error', 200, 'اجازه حذف برای این رنگ داده نشد.');
        }

        $res = $model->delete_it($table, 'id=:id AND deletable=:del', ['id' => $id, 'del' => 1]);
        if ($res) {
            message('success', 200, 'رنگ با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
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
        $this->data['form_token'] = $form->csrfToken('addStaticPage');
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

    public function manageFactorAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['factors'] = $model->select_it(null, 'factors AS f', [
            'f.id', 'f.factor_code', 'f.first_name', 'f.last_name', 'f.mobile', 'f.payment_title', 'f.payment_status', 'f.shipping_title',
            'f.send_status', 'f.final_amount', 'f.want_factor', 'f.payment_date', 'f.order_date'
        ]);

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده سفارشات');

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

                if($sendStatus != $values['send_status']) {
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

    public function addCategoryAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['catVals'] = [];

        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name', 'level'], null, [], null, 'level ASC');

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addCategory');
        $form->setFieldsName(['name', 'parent', 'level', 'keywords', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name', 'parent', 'level', 'status'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('categories', 'category_name=:name', ['name' => $values['name']])) {
                    $form->setError('این دسته‌بندی وجود دارد. لطفا دوباره تلاش کنید.');
                }
                $form->isIn('parent', array_merge([0], array_column($this->data['categories'], 'id')), 'دسته‌بندی والد نامعتبر است.')
                    ->isIn('level', [1, 2, 3], 'سطح دسته نامعتبر است.');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $common = new CommonModel();
                $code = $common->generate_random_unique_code('categories', 'category_code', 'CAT_', 8, 15, 10, CommonModel::DIGITS);

                if (empty($code)) {
                    $form->setError('مشکل در ایجاد کد اختصاصی پیش آمده است. لطفا مجددا تلاش نمایید!');
                    return;
                }

                $code = 'CAT_' . $code;

                // TODO: remove while. It could be sooo simpler
                $allParents = '';
                $category = trim($values['parent']);
                while ($model->it_count('categories', 'id=:id AND parent_id!=:parent', ['id' => $category, 'parent' => 0])) {
                    $res = $model->select_it(null, 'categories', ['id', 'parent_id'],
                        'id=:id', ['id' => $category])[0];
                    $allParents .= $res['id'] . ',';
                    $category = $res['parent_id'];
                }
                $t = $model->select_it(null, 'categories', 'id',
                    'id=:id', ['id' => $category]);
                if (count($t)) {
                    $allParents .= $t[0]['id'];
                }
                //

                $res = $model->insert_it('categories', [
                    'category_code' => $code,
                    'category_name' => trim($values['name']),
                    'parent_id' => trim($values['parent']),
                    'all_parents' => $allParents,
                    'level' => trim($values['level']),
                    'keywords' => trim($values['keywords']),
                    'deletable' => 1,
                    'status' => $form->isChecked('status') ? 1 : 0
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

        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name', 'level'], null, [], null, 'level ASC');

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
        $this->data['catVals'] = [];

        $this->data['catVals'] = $model->select_it(null, 'categories', ['category_name'], 'id=:id', ['id' => $param[0]])[0];
        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name', 'level'], null, [], null, 'level ASC');

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editCategory');
        $form->setFieldsName(['name', 'parent', 'level', 'keywords', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name', 'parent', 'level', 'status'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($this->data['catVals']['category_name'] != $values['name']) {
                    if ($model->is_exist('categories', 'category_name=:name', ['name' => $values['name']])) {
                        $form->setError('این دسته‌بندی وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
                $form->isIn('parent', array_merge([0], array_column($this->data['categories'], 'id')), 'دسته‌بندی والد نامعتبر است.')
                    ->isIn('level', [1, 2, 3], 'سطح دسته نامعتبر است.');
                if ($values['parent'] == $this->data['param'][0]) {
                    $form->setError('دسته نمی‌تواند والد خود باشد.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                // TODO: remove while. It could be sooo simpler
                $allParents = '';
                $category = trim($values['parent']);
                while ($model->it_count('categories', 'id=:id AND parent_id!=:parent', ['id' => $category, 'parent' => 0])) {
                    $res = $model->select_it(null, 'categories', ['id', 'parent_id'],
                        'id=:id', ['id' => $category])[0];
                    $allParents .= $res['id'] . ',';
                    $category = $res['parent_id'];
                }
                $t = $model->select_it(null, 'categories', 'id',
                    'id=:id', ['id' => $category]);
                if (count($t)) {
                    $allParents .= $t[0]['id'];
                }
                //

                $res = $model->update_it('categories', [
                    'category_name' => trim($values['name']),
                    'parent_id' => trim($values['parent']),
                    'all_parents' => $allParents,
                    'level' => trim($values['level']),
                    'keywords' => trim($values['keywords']),
                    'status' => $form->isChecked('status') ? 1 : 0
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
        $this->data['categories'] = $model->select_it(null, 'categories', '*', null, [], null, [
            'level ASC', 'priority ASC', 'id ASC'
        ]);

        for ($i = 0; $i < count($this->data['categories']); $i++) {
            $this->data['categories'][$i]['parent'] = !is_null($this->data['categories'][$i]['parent_id']) ?
                ($this->data['categories'][$i]['parent_id'] != 0 ?
                    $model->select_it(null, 'categories', 'category_name',
                        'id=:pid', ['pid' => $this->data['categories'][$i]['parent_id']])[0]['category_name'] : 'دسته اصلی') :
                "<i class='icon-dash text-grey-300' aria-hidden='true'></i>";
        }

        $this->data['levels'] = $model->select_it(null, 'categories', 'level', 'level!=:lvl', ['lvl' => 0], ['level']);

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده دسته‌بندی‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Category/manageCategory');
    }

    public function managePriorityAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !isset($param[1]) || $param[0] != 'level' || !is_numeric($param[1]) ||
            !$model->is_exist('categories', 'level=:lvl', ['lvl' => $param[1]])) {
            $this->redirect(base_url('admin/manageCategory'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editPriority');
        $form->setFieldsName(['id', 'priority'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['id', 'priority'], 'آیتم‌ها دستکاری شده‌اند!!');

                $this->data['catItems'] = array_column($model->select_it(null, 'categories', 'id', 'level=:lvl', ['lvl' => $this->data['param'][1]]), 'id');
                $same = array_intersect($values['id'], $this->data['catItems']);
                if (count($same) != count($this->data['catItems'])) {
                    $form->setError('آیتم‌ها دستکاری شده‌اند!!');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $model->transactionBegin();

                foreach ($values['id'] as $key => $id) {
                    $res = $model->update_it('categories', [
                        'priority' => $values['priority'][$key]
                    ], 'id=:id AND level=:lvl', ['id' => $id, 'lvl' => $this->data['param'][1]]);

                    if (!$res) break;
                }

                if ($res) {
                    $model->transactionComplete();
                } else {
                    $model->transactionRollback();
                    $form->setError('خطا در انجام عملیات! مجددا تلاش نمایید.');
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

        $this->data['items'] = $model->select_it(null, 'categories', ['id', 'category_name', 'priority', 'parent_id'],
            'level=:lvl', ['lvl' => $param[1]], null, ['priority ASC', 'id ASC']);

        for ($i = 0; $i < count($this->data['items']); $i++) {
            $this->data['items'][$i]['parent'] = !is_null($this->data['items'][$i]['parent_id']) ? ($this->data['items'][$i]['parent_id'] != 0 ? $model->select_it(null, 'categories', 'category_name', 'id=:pid', ['pid' => $this->data['items'][$i]['parent_id']])[0]['category_name'] : 'دسته اصلی') : "<i class='icon-dash text-grey-300' aria-hidden='true'></i>";
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'تغییر اولویت‌های سطح', $param[1]);

        $this->data['js'][] = $this->asset->script('be/js/core/libraries/jquery_ui/interactions.min.js');
        $this->data['js'][] = $this->asset->script('be/js/core/libraries/jquery_ui/touch.min.js');

        $this->_render_page('pages/be/Category/managePriority');
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

    public function addTitleAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['tiVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addTitle');
        $form->setFieldsName(['name'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('titles_property',
                    'title_name=:name', ['name' => $values['name']])) {
                    $form->setError('این گروه‌بندی وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('titles_property', [
                    'title_name' => trim($values['name'])
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
                $this->data['tiVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن گروه‌بندی');

        $this->_render_page('pages/be/Property-Title/addTitle');
    }

    public function editTitleAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('titles_property', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageTitle'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['tiVals'] = [];

        $this->data['tiVals'] = $model->select_it(null, 'titles_property', ['title_name'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editTitle');
        $form->setFieldsName(['name'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($this->data['tiVals']['title_name'] != $values['name']) {
                    if ($model->is_exist('titles_property',
                        'title_name=:name', ['name' => $values['name']])) {
                        $form->setError('این عنوان وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('titles_property', [
                    'title_name' => trim($values['name'])
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
                $this->data['tiVals'] = $form->getValues();
            }
        }

        $this->data['tiVals'] = $model->select_it(null, 'titles_property', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش ویژگی', $this->data['tiVals']['title_name']);

        $this->_render_page('pages/be/Property-Title/editTitle');
    }

    public function manageTitleAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['titles'] = $model->select_it(null, 'titles_property');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده گروه‌بندی ویژگی‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Property-Title/manageTitle');
    }

    public function deleteTitleAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'titles_property';
        if (!isset($id)) {
            message('error', 200, 'ویژگی نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'ویژگی وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'ویژگی با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addPropertyAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['propVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addProperty');
        $form->setFieldsName(['name'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('properties',
                    'property_name=:name', ['name' => $values['name']])) {
                    $form->setError('این ویژگی وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('properties', [
                    'property_name' => trim($values['name'])
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
                $this->data['propVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن ویژگی');

        $this->_render_page('pages/be/Property/addProperty');
    }

    public function editPropertyAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('properties', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageProperty'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['propVals'] = [];

        $this->data['propVals'] = $model->select_it(null, 'properties', ['property_name', 'latin_name'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editProperty');
        $form->setFieldsName(['name'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['name'], 'فیلدهای ضروری را خالی نگذارید.');
                if ($this->data['propVals']['property_name'] != $values['name']) {
                    if ($model->is_exist('properties',
                        'property_name=:name', ['name' => $values['name']])) {
                        $form->setError('این ویژگی وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('properties', [
                    'property_name' => trim($values['name'])
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
                $this->data['propVals'] = $form->getValues();
            }
        }

        $this->data['propVals'] = $model->select_it(null, 'properties', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش ویژگی', $this->data['propVals']['property_name']);

        $this->_render_page('pages/be/Property/editProperty');
    }

    public function managePropertyAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['properties'] = $model->select_it(null, 'properties');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده ویژگی‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Property/manageProperty');
    }

    public function deletePropertyAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'properties';
        if (!isset($id)) {
            message('error', 200, 'ویژگی نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'ویژگی وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'ویژگی با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addCategoryPropertyAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['catPropVals'] = [];

        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name', 'level'], 'level!=:lvl', ['lvl' => 0], null, 'level ASC');

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addCategoryProperty');
        $form->setFieldsName([''])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired([''], 'فیلدهای ضروری را خالی نگذارید.');
                if ($model->is_exist('categories', 'property_name=:name', ['name' => $values['name']])) {
                    $form->setError('این ویژگی وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('categories', [
                    'category_name' => trim($values['name']),
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
                $this->data['catPropVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن ویژگی‌های دسته‌بندی');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        $this->_render_page('pages/be/Category-Property/addCategoryProperty');
    }

    public function addProductAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['pVals'] = [];

        // Select all we need in page
        $this->data['colors'] = $model->select_it(null, 'colors');
        $this->data['brands'] = $model->select_it(null, 'brands', ['id', 'brand_name']);
        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name', 'level'], null, [], null, 'level ASC');
        $this->data['products'] = $model->select_it(null, 'products', ['id', 'product_title']);

        $this->data['properties'] = $model->select_it(null, 'properties');
        $this->data['titles'] = $model->select_it(null, 'titles_property');

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addProduct');
        $form->setFieldsName([
            'image', 'title', 'latin', 'brand', 'category',
            'discount', 'discount-unit', 'keywords', 'status',
            'color', 'imageGallery', 'p-keywords', 'related',
            'description', 'title-property', 'guarantee', 'guarantee-price'
        ])->setDefaults('status', 'off')
            ->setDefaults('related', [0 => 0])
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                // Create title-property structure
                $newTP = [];
                $counter = 0;
                foreach ($values['title-property'] as $k => $value) {
                    if (is_array($value)) {
                        if (is_array($value[0])) {
                            $newTP[$counter]['value'][] = $value[0][0] ?? '';
                        } else {
                            $newTP[$counter]['property'][] = $value[0];
                        }
                    } else {
                        if ($k != 0) {
                            $counter++;
                        }
                        $newTP[$counter]['title'] = $value ?? '';
                    }
                }

                // Bind it to old title-property
                $values['title-property'] = $newTP;

                // Convert persian numbers to english
                $values['color']['count'] = convertNumbersToPersian($values['color']['count'], true);
                $values['color']['price'] = convertNumbersToPersian($values['color']['price'], true);

                // Remove incorrect color input
                $colorMinC = min(count($values['color']['color']), count($values['color']['count']), count($values['color']['price']));
                for ($i = 0; $i < $colorMinC; $i++) {
                    if (empty($values['color']['color'][$i]) || empty($values['color']['count'][$i]) || empty($values['color']['price'][$i])
                        || !is_numeric($values['color']['count'][$i]) || !is_numeric($values['color']['price'][$i])) {
                        unset($values['color']['color'][$i]);
                        unset($values['color']['count'][$i]);
                        unset($values['color']['price'][$i]);
                    }
                }

                $form->isRequired(['image', 'title', 'brand', 'category', 'status'], 'فیلدهای ضروری را خالی نگذارید.');

                if (!file_exists($values['image'])) {
                    $form->setError('تصویر شاخص انتخاب شده، نامعتبر است.');
                }

                if (!count($values['color']['color'])) {
                    $form->setError('انتخاب حداقل یک رنگ اجباری است.');
                    $values['color']['color'][] = '';
                    $values['color']['count'][] = '';
                    $values['color']['price'][] = '';
                }
                // Calculate color(s) iteration . this must be right after check of color
                $this->data['color_iteration'] = max(count($values['color']['color']), count($values['color']['count']), count($values['color']['price']));

                $values['imageGallery'] = array_filter($values['imageGallery'], function ($img) {
                    return file_exists($img);
                });
                if (!count($values['imageGallery'])) {
                    $values['imageGallery'] = [0 => ''];
                    $form->setError('انتخاب حداقل یک تصویر برای گالری اجباری است.');
                }

                if (!in_array($values['brand'], array_column($this->data['brands'], 'id'))) {
                    $form->setError('برند انتخاب شده نامعتبر است.');
                }
                if (!in_array($values['category'], array_column($this->data['categories'], 'id'))) {
                    $form->setError('دسته‌بندی انتخاب شده نامعتبر است.');
                }

                $values['related'] = array_filter($values['related'], function ($product) use ($model) {
                    return $model->is_exist('products', 'id=:id', ['id' => $product]);
                });

                $form->isIn('discount-unit', [1, 2], 'واحد تخفیف نامعتبر است.');

                if ($model->is_exist('products',
                    'product_title=:name', ['name' => $values['title']])) {
                    $form->setError('این محصول وجود دارد. لطفا دوباره تلاش کنید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $common = new CommonModel();
                $code = $common->generate_random_unique_code('products', 'product_code', 'PRD_', 8, 15, 10, CommonModel::DIGITS);

                if (empty($code)) {
                    $form->setError('مشکل در ایجاد کد اختصاصی پیش آمده است. لطفا مجددا تلاش نمایید!');
                    return;
                }

                $code = 'PRD_' . $code;

                $stockCount = array_reduce($values['color']['count'], function (&$stockCount, $item) {
                    return $stockCount + (int)$item;
                }, 0);

                $model->transactionBegin();

                $res = $model->insert_it('products', [
                    'product_code' => $code,
                    'product_title' => trim($values['title']),
                    'latin_title' => trim($values['latin']),
                    'brand' => trim($values['brand']),
                    'category' => trim($values['category']),
                    'image' => trim($values['image']),
                    'body' => trim($values['description']),
                    'stock_count' => convertNumbersToPersian($stockCount, true),
                    'discount' => !empty($values['discount']) ? convertNumbersToPersian($values['discount'], true) : 0,
                    'discount_unit' => $values['discount-unit'],
                    'property' => json_encode($values['title-property']),
                    'property_abstract' => $values['p-keywords'],
                    'related' => is_array($values['related']) ? implode(',', convertNumbersToPersian($values['related'], true)) : '',
                    'user_created_id' => $this->auth->getIdentity()->id,
                    'keywords' => trim($values['keywords']),
                    'publish' => $form->isChecked('status') ? 1 : 0,
                    'available' => 1,
                    'created_on' => time()
                ], [], true);

                $res2 = false;
                $count = count($values['color']['color']);
                for ($i = 0; $i < $count; $i++) {
                    $res2 = $model->insert_it('products_colors', [
                        'product_id' => $res,
                        'color_id' => $values['color']['color'][$i],
                        'price' => convertNumbersToPersian($values['color']['price'][$i], true),
                        'count' => convertNumbersToPersian($values['color']['count'][$i], true)
                    ]);
                }

                $res3 = false;
                foreach ($values['imageGallery'] as $img) {
                    $res3 = $model->insert_it('products_image', [
                        'product_id' => $res,
                        'image' => $img
                    ]);
                }

                $res4 = $model->insert_it('products_guarantee', [
                    'product_id' => $res,
                    'guarantee_title' => $values['guarantee'],
                    'guarantee_price' => !empty($values['guarantee-price']) ? convertNumbersToPersian($values['guarantee-price'], true) : 0
                ]);

                if (!$res || !$res2 || !$res3 || !$res4) {
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
                $this->data['pVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن محصول');

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');
        $this->data['js'][] = $this->asset->script('be/js/pickColorJs.js');
        $this->data['js'][] = $this->asset->script('be/js/propertyJs.js');
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        $this->_render_page([
            'pages/be/Product/addProduct',
            'templates/be/efm',
            'templates/be/browser-tiny-func'
        ]);
    }

    public function editProductAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('products', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageProduct'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['pVals'] = [];

        // Select all we need in page
        $this->data['colors'] = $model->select_it(null, 'colors');
        $this->data['brands'] = $model->select_it(null, 'brands', ['id', 'brand_name']);
        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name', 'level'], null, [], null, 'level ASC');
        $this->data['products'] = $model->select_it(null, 'products', ['id', 'product_title'], 'id!=:id', ['id' => $param[0]]);

        $this->data['properties'] = $model->select_it(null, 'properties');
        $this->data['titles'] = $model->select_it(null, 'titles_property');

        $this->data['pVals'] = $model->select_it(null, 'products', ['product_title'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editProduct');
        $form->setFieldsName([
            'image', 'title', 'latin', 'brand', 'category',
            'discount', 'discount-unit', 'keywords', 'status', 'available',
            'color', 'imageGallery', 'p-keywords', 'related',
            'description', 'title-property', 'guarantee', 'guarantee-price'
        ])->setDefaults(['status', 'available'], 'off')
            ->setDefaults('related', [0 => 0])
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                // Create title-property structure
                $newTP = [];
                $counter = 0;
                foreach ($values['title-property'] as $k => $value) {
                    if (is_array($value)) {
                        if (is_array($value[0])) {
                            $newTP[$counter]['value'][] = $value[0][0] ?? '';
                        } else {
                            $newTP[$counter]['property'][] = $value[0];
                        }
                    } else {
                        if ($k != 0) {
                            $counter++;
                        }
                        $newTP[$counter]['title'] = $value ?? '';
                    }
                }

                // Bind it to old title-property
                $values['title-property'] = $newTP;

                // Convert persian numbers to english
                $values['color']['count'] = convertNumbersToPersian($values['color']['count'], true);
                $values['color']['price'] = convertNumbersToPersian($values['color']['price'], true);

                // Remove incorrect color input
                $colorMinC = min(count($values['color']['color']), count($values['color']['count']), count($values['color']['price']));
                for ($i = 0; $i < $colorMinC; $i++) {
                    if (empty($values['color']['color'][$i]) || empty($values['color']['count'][$i]) || empty($values['color']['price'][$i])
                        || !is_numeric($values['color']['count'][$i]) || !is_numeric($values['color']['price'][$i])) {
                        unset($values['color']['color'][$i]);
                        unset($values['color']['count'][$i]);
                        unset($values['color']['price'][$i]);
                    }
                }

                $form->isRequired(['image', 'title', 'latin', 'brand', 'category', 'status'], 'فیلدهای ضروری را خالی نگذارید.');

                if (!file_exists($values['image'])) {
                    $form->setError('تصویر شاخص انتخاب شده، نامعتبر است.');
                }

                if (!count($values['color']['color'])) {
                    $form->setError('انتخاب حداقل یک رنگ اجباری است.');
                    $values['color']['color'][] = '';
                    $values['color']['count'][] = '';
                    $values['color']['price'][] = '';
                }

                $values['imageGallery'] = array_filter($values['imageGallery'], function ($img) {
                    return file_exists($img);
                });
                if (!count($values['imageGallery'])) {
                    $form->setError('انتخاب حداقل یک تصویر برای گالری اجباری است.');
                }

                if (!in_array($values['brand'], array_column($this->data['brands'], 'id'))) {
                    $form->setError('برند انتخاب شده نامعتبر است.');
                }
                if (!in_array($values['category'], array_column($this->data['categories'], 'id'))) {
                    $form->setError('دسته‌بندی انتخاب شده نامعتبر است.');
                }

                $values['related'] = array_filter($values['related'], function ($product) use ($model) {
                    return $model->is_exist('products', 'id=:id', ['id' => $product]);
                });

                $form->isIn('discount-unit', [1, 2], 'واحد تخفیف نامعتبر است.');

                if ($this->data['pVals']['product_title'] != $values['title'] && $this->data['pVals']['latin_title'] != $values['latin']) {
                    if ($model->is_exist('products',
                        'product_title=:name', ['name' => $values['title']])) {
                        $form->setError('این محصول وجود دارد. لطفا دوباره تلاش کنید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $stockCount = array_reduce($values['color']['count'], function (&$stockCount, $item) {
                    return $stockCount + (int)$item;
                }, 0);

                $model->transactionBegin();

                $res = $model->update_it('products', [
                    'product_title' => trim($values['title']),
                    'latin_title' => trim($values['latin']),
                    'brand' => trim($values['brand']),
                    'category' => trim($values['category']),
                    'image' => trim($values['image']),
                    'body' => trim($values['description']),
                    'stock_count' => convertNumbersToPersian($stockCount, true),
                    'discount' => !empty($values['discount']) ? convertNumbersToPersian($values['discount'], true) : 0,
                    'discount_unit' => $values['discount-unit'],
                    'property' => json_encode($values['title-property']),
                    'property_abstract' => $values['p-keywords'],
                    'related' => is_array($values['related']) ? implode(',', $values['related']) : '',
                    'user_updated_id' => $this->auth->getIdentity()->id,
                    'keywords' => trim($values['keywords']),
                    'publish' => $form->isChecked('status') ? 1 : 0,
                    'available' => $form->isChecked('available') ? 1 : 0
                ], 'id=:id', ['id' => $this->data['param'][0]]);

                $res2 = false;
                $count = count($values['color']['color']);
                $res5 = $model->delete_it('products_colors', 'product_id=:id', ['id' => $this->data['param'][0]]);
                for ($i = 0; $i < $count; $i++) {
                    $res2 = $model->insert_it('products_colors', [
                        'product_id' => $this->data['param'][0],
                        'color_id' => $values['color']['color'][$i],
                        'price' => convertNumbersToPersian($values['color']['price'][$i], true),
                        'count' => convertNumbersToPersian($values['color']['count'][$i], true)
                    ]);
                }

                $res3 = false;
                $res6 = $model->delete_it('products_image', 'product_id=:id', ['id' => $this->data['param'][0]]);
                foreach ($values['imageGallery'] as $img) {
                    $res3 = $model->insert_it('products_image', [
                        'product_id' => $this->data['param'][0],
                        'image' => $img
                    ]);
                }

                $res4 = $model->update_it('products_guarantee', [
                    'guarantee_title' => $values['guarantee'],
                    'guarantee_price' => !empty($values['guarantee-price']) ? convertNumbersToPersian($values['guarantee-price'], true) : 0
                ], 'product_id=:id', ['id' => $this->data['param'][0]]);

                if (!$res || !$res2 || !$res3 || !$res4 || !$res5 || !$res6) {
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

        $this->data['pVals'] = $model->select_it(null, 'products', '*', 'id=:id', ['id' => $param[0]])[0];
        // Convert related column to array
        $this->data['pVals']['related'] = array_map('trim', explode(',', $this->data['pVals']['related']));
        // Decode properties from text to array
        $this->data['pVals']['title-property'] = json_decode($this->data['pVals']['property'], true);

        // Get product other information from other tables
        // -Guarantees
        $guarantees = $model->select_it(null, 'products_guarantee',
            ['guarantee_title', 'guarantee_price'], 'product_id=:id', ['id' => $param[0]]);
        // -Colors
        $colors = $model->select_it(null, 'products_colors', ['color_id', 'price', 'count'],
            'product_id=:id', ['id' => $param[0]]);
        // -ImageGallery
        $images = $model->select_it(null, 'products_image', 'image', 'product_id=:id', ['id' => $param[0]]);

        // -Guarantees
        // Here we just have one guarantee per product for now!
        $this->data['pVals']['guarantee_title'] = $guarantees[0]['guarantee_title'];
        $this->data['pVals']['guarantee_price'] = $guarantees[0]['guarantee_price'];
        // -Colors
        $this->data['pVals']['color'] = $colors;
        // -ImageGallery
        $this->data['pVals']['imageGallery'] = array_column($images, 'image');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش محصول', $this->data['pVals']['product_title']);

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');
        $this->data['js'][] = $this->asset->script('be/js/pickColorJs.js');
        $this->data['js'][] = $this->asset->script('be/js/propertyJs.js');
        $this->data['js'][] = $this->asset->script('be/js/tinymce/tinymce.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        $this->_render_page([
            'pages/be/Product/editProduct',
            'templates/be/efm',
            'templates/be/browser-tiny-func'
        ]);
    }

    public function manageProductAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $join1 = $model->join_it(null, 'products AS p', 'brands AS b',
            ['p.id', 'p.product_code', 'p.product_title', 'p.latin_title', 'p.image', 'p.category', 'p.publish',
                'p.stock_count', 'p.sold_count', 'b.brand_name AS brand', 'p.available'], 'p.brand=b.id',
            null, null, null, null, null, null, true, 'LEFT');
        $this->data['products'] = $model->join_it($join1, 'categories AS c', 'sub',
            ['sub.id', 'sub.product_code', 'sub.product_title', 'sub.latin_title', 'sub.image', 'sub.publish',
                'sub.stock_count', 'sub.sold_count', 'sub.brand', 'sub.available', 'c.category_name AS category'],
            'sub.category=c.id', null, null, null, null, null, null,
            false, 'RIGHT');

        // Add all colors with their price to products array
        $c = count($this->data['products']);
        for ($i = 0; $i < $c; $i++) {
            $colors = $model->join_it(null, 'products_colors AS pc', 'colors AS c', [
                'pc.price', 'c.color_hex', 'c.color_name'
            ], 'pc.color_id=c.id', 'pc.product_id=:id', ['id' => $this->data['products'][$i]['id']],
                null, null, null, null, false, 'LEFT');
            $this->data['products'][$i]['colors'] = $colors;
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده محصولات');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Product/manageProduct');
    }

    public function deleteProductAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'products';
        if (!isset($id)) {
            message('error', 200, 'محصول نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'محصول وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'محصول با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function productAvailabilityAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = $_POST['postedId'];
        $stat = $_POST['stat'];
        $table = 'products';
        if (!isset($id) || !isset($stat) || !in_array($stat, [0, 1])) {
            message('error', 200, 'ورودی نامعتبر است.');
        }

        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'محصول وجود ندارد.');
        }

        $res = $model->update_it($table, ['available' => $stat], 'id=:id', ['id' => $id]);
        if ($res) {
            if ($stat == 1) {
                message('success', 200, 'موجودی محصول فعال شد.');
            } else {
                message('warning', 200, 'موجودی محصول غیر فعال شد.');
            }
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function festivalProductAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('festivals', 'id=:id', ['id' => $param[0]])) {
            $this->error->show_404('admin_notfound', [], false, true);
        }

        $this->data['param'] = $param;

        $this->data['products'] = $model->select_it(null, 'products', ['id', 'product_title']);
        $this->data['festival'] = $model->select_it(null, 'festivals', 'festival_title',
            'id=:id', ['id' => $param[0]])[0]['festival_title'];

        $this->data['errors'] = [];
        $this->data['fesPro'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addFestivalProduct');
        $form->setFieldsName(['product', 'discount'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                $values['discount'] = convertNumbersToPersian($values['discount'], true);

                $form->isRequired(['product', 'discount'], 'فیلدهای ضروری را خالی نگذارید.');
                $form->validate('numeric', 'discount', 'تخفیف باید عدد باشد.');

                if (!$model->is_exist('products', 'id=:id', ['id' => $values['product']])) {
                    $form->setError('محصول انتخاب شده نامعتبر است!');
                }
                $form->isInRange('discount', 0, 100, 'تخفیف باید عددی بین ۰ و ۱۰۰ باشد.');
                if ($model->is_exist('products_festivals', 'product_id=:pId AND festival_id=:fId',
                    ['pId' => $values['product'], 'fId' => $this->data['param'][0]])) {
                    $form->setError('این محصول در این جشنواره وجود دارد. لطفا دوباره تلاش کنید.');
                }

                if (!count($form->getError())) {
                    $fpInClause = '';
                    $fpParams = [];
                    if ($this->data['activeFestivalsId'][0] != -1) {
                        foreach ($this->data['activeFestivalsId'] as $key => $fId) {
                            $fpInClause .= ':fp' . ($key + 1) . ',';
                            $fpParams['fp' . ($key + 1)] = $fId;
                        }
                        $fpInClause = rtrim($fpInClause, ',');
                        $fpInClause = ' AND festival_id IN(' . $fpInClause . ')';
                    }

                    $thisProductFestivalIds = $model->select_it(null, 'products_festivals', ['festival_id'],
                        'product_id=:pId ' . $fpInClause,
                        array_merge($fpParams, ['pId' => $values['product']]));
                    if (count($thisProductFestivalIds)) {
                        if ($this->setting['admin']['panel']['offer_reaction'] == self::OFFER_REACTION_SHOW_ERROR) {
                            $form->setError('این محصول در پیشنهاد(های) فعال دیگری وجود دارد.');
                        } else if ($this->setting['admin']['panel']['offer_reaction'] == self::OFFER_REACTION_DISABLE_OTHERS) {
                            $fpInClause = '';
                            $fpParams = [];
                            foreach ($thisProductFestivalIds as $key => $fId) {
                                $fpInClause .= ':fp' . ($key + 1) . ',';
                                $fpParams['fp' . ($key + 1)] = $fId['festival_id'];
                            }
                            $fpInClause = rtrim($fpInClause, ',');
                            $fpInClause = 'id IN(' . $fpInClause . ')';

                            $res = $model->update_it('festivals', ['status' => 0],
                                $fpInClause, $fpParams);
                            if (!$res) {
                                $form->setError('خطا در انجام عملیات!');
                            } else {
                                $this->data['warning'] = 'پیشنهادهای فعال دارای این محصول، غیر فعال شده‌اند.';
                            }
                        }
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('products_festivals', [
                    'product_id' => $values['product'],
                    'festival_id' => $this->data['param'][0],
                    'discount' => $values['discount']
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
                $this->data['fesPro'] = $form->getValues();
            }
        }

        $this->data['festivalProducts'] = $model->join_it(null, 'products_festivals AS pf', 'products AS p', [
            'pf.id AS f_id', 'pf.discount', 'p.id', 'p.product_code', 'p.product_title', 'p.latin_title', 'p.image'
        ], 'pf.product_id=p.id', 'festival_id=:fId', ['fId' => $param[0]],
            null, null, null, null, false, 'LEFT');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جشنواره', $this->data['festival']);

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Festival/festivalProduct');
    }

    public function deleteFestivalProductAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'products_festivals';
        if (!isset($id)) {
            message('error', 200, 'محصول جشنواره نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'محصول جشنواره وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'محصول جشنواره با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function addFestivalAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['fesVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addFestival');
        $form->setFieldsName(['title', 'set', 'expire', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['title', 'set', 'expire', 'status'], 'فیلدهای ضروری را خالی نگذارید.');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $common = new CommonModel();
                $code = $common->generate_random_unique_code('festivals', 'festival_code', 'FES_', 8, 15, 10, CommonModel::DIGITS);

                if (empty($code)) {
                    $form->setError('مشکل در ایجاد کد اختصاصی پیش آمده است. لطفا مجددا تلاش نمایید!');
                    return;
                }

                $code = 'FES_' . $code;

                $res = $model->insert_it('festivals', [
                    'festival_code' => $code,
                    'festival_title' => trim($values['title']),
                    'festival_set_date' => trim($values['set']),
                    'festival_expire_date' => trim($values['expire']),
                    'status' => $form->isChecked('status') ? 1 : 0
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
                $this->data['fesVals'] = $form->getValues();
            }
        }

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/persian-datepicker-custom.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-date.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-datepicker.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/forms/tags/tagsinput.min.js');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن جشنواره');

        $this->_render_page('pages/be/Festival/addFestival');

    }

    public function editFestivalAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('festivals', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageFestival'));
        }

        $this->data['param'] = $param;

        $model = new Model();

        $currentStatus = $model->select_it(null, 'festivals', 'status', 'id=:id', ['id' => $param[0]])[0]['status'];

        $this->data['errors'] = [];
        $this->data['fesVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addFestival');
        $form->setFieldsName(['title', 'set', 'expire', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form, $currentStatus) {
                $form->isRequired(['title', 'set', 'expire', 'status'], 'فیلدهای ضروری را خالی نگذارید.');

                if ($currentStatus == 0 && $form->isChecked('status')) {
                    $fpInClause = '';
                    $fpParams = [];
                    if ($this->data['activeFestivalsId'][0] != -1) {
                        foreach ($this->data['activeFestivalsId'] as $key => $fId) {
                            $fpInClause .= ':fp' . ($key + 1) . ',';
                            $fpParams['fp' . ($key + 1)] = $fId;
                        }
                        $fpInClause = rtrim($fpInClause, ',');
                        $fpInClause = 'festival_id IN(' . $fpInClause . ')';
                    }

                    $thisFestivalProductIds = $model->select_it(null, 'products_festivals', ['product_id'],
                        'festival_id=:fId', ['fId' => $this->data['param'][0]]);

                    if (count($thisFestivalProductIds)) {
                        $pfInClause = '';
                        $pfParams = [];
                        foreach ($thisFestivalProductIds as $key => $fId) {
                            $pfInClause .= ':pf' . ($key + 1) . ',';
                            $pfParams['pf' . ($key + 1)] = $fId['product_id'];
                        }
                        $pfInClause = rtrim($pfInClause, ',');
                        $pfInClause = 'product_id IN(' . $pfInClause . ')';
                        if (!empty($fpInClause)) $pfInClause = ' AND ' . $pfInClause;

                        if ($model->is_exist('products_festivals', $fpInClause . $pfInClause, array_merge($fpParams, $pfParams))) {
                            if ($this->setting['admin']['panel']['offer_activation_reaction'] == self::OFFER_ACTIVATION_REACTION_SHOW_ERROR) {
                                $form->setError('این پیشنهاد دارای محصولاتی مشترک با پیشنهاد(های) فعال دیگری است.');
                            } else if ($this->setting['admin']['panel']['offer_activation_reaction'] == self::OFFER_ACTIVATION_REACTION_DEACTIVATE_OTHERS) {
                                $fpInClause = '';
                                $fpParams = [];
                                if ($this->data['activeFestivalsId'][0] != -1) {
                                    foreach ($this->data['activeFestivalsId'] as $key => $fId) {
                                        $fpInClause .= ':fp' . ($key + 1) . ',';
                                        $fpParams['fp' . ($key + 1)] = $fId;
                                    }
                                    $fpInClause = rtrim($fpInClause, ',');
                                    $fpInClause = 'id IN(' . $fpInClause . ')';
                                }
                                $res = $model->update_it('festivals', ['status' => 0], $fpInClause, $fpParams);
                                if (!$res) {
                                    $form->setError('خطا در انجام عملیات!');
                                } else {
                                    $this->data['warning'] = 'پیشنهاد(های) فعال دیگر که دارای محصول مشترک با این پیشنهاد بودند، غیرفعال شده‌اند.';
                                }
                            } else if ($this->setting['admin']['panel']['offer_activation_reaction'] == self::OFFER_ACTIVATION_REACTION_DELETE_OTHER_PRODUCTS) {
                                $res = $model->delete_it('products_festivals', $fpInClause . $pfInClause, array_merge($fpParams, $pfParams));
                                if (!$res) {
                                    $form->setError('خطا در انجام عملیات!');
                                } else {
                                    $this->data['warning'] = 'محصولات مشترک در سایر پیشنهاد(های) فعال دیگر حذف شده‌اند.';
                                }
                            }
                        }
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('festivals', [
                    'festival_title' => trim($values['title']),
                    'festival_set_date' => trim($values['set']),
                    'festival_expire_date' => trim($values['expire']),
                    'status' => $form->isChecked('status') ? 1 : 0
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
                $this->data['fesVals'] = $form->getValues();
            }
        }

        $this->data['fesVals'] = $model->select_it(null, 'festivals', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش جشنواره');

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/persian-datepicker-custom.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-date.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/persian-datepicker.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/pickers/spectrum.js');

        $this->_render_page('pages/be/Festival/editFestival');
    }

    public function manageFestivalAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['festivals'] = $model->select_it(null, 'festivals');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده جشنواره‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Festival/manageFestival');
    }

    public function deleteFestivalAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'festivals';
        if (!isset($id)) {
            message('error', 200, 'جشنواره نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'جشنواره وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id]);
        if ($res) {
            message('success', 200, 'جشنواره با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function manageCommentAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $cmtPr = $model->join_it(null, 'comments AS c', 'products AS p', [
            'c.id', 'c.user_id', 'c.product_id', 'c.status', 'c.comment_date', 'p.product_title', 'p.image', 'p.product_code'
        ], 'c.product_id=p.id', null, [], null, null, null, null, true, 'LEFT');
        $this->data['comments'] = $model->join_it($cmtPr, 'users AS u', 'c', [
            'c.id', 'c.user_id', 'c.product_id', 'c.status', 'c.comment_date', 'c.product_title', 'c.image',
            'c.product_code', 'u.username', 'u.first_name', 'u.last_name'
        ], 'c.user_id=u.id', null, [], null, 'c.comment_date DESC', null, null, false, 'RIGHT');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مدیریت نظرات');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Comment/manageComment');
    }

    public function setFestivalToMainOneAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $stat = $_POST['stat'];
        $table = 'festivals';
        if (!isset($id) || !isset($stat) || !in_array($stat, [0, 1])) {
            message('error', 200, 'جشنواره نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'جشنواره وجود ندارد.');
        }

        $res2 = $model->update_it($table, [
            'is_main' => 0
        ]);
        $res = $model->update_it($table, [
            'is_main' => $stat
        ], 'id=:id', ['id' => $id]);
        if ($res && $res2) {
            if ($stat == 1) {
                message('success', 200, 'جشنواره به عنوان جشنواره اصلی انتخاب شد.');
            } else {
                message('warning', 200, 'جشنواره از درجه جشنواره اصلی برداشته شد.');
            }
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
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

    public function addSliderAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        $this->data['errors'] = [];
        $this->data['sldVals'] = [];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('addSlider');
        $form->setFieldsName(['image', 'link', 'priority', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post', [], ['status']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['image', 'link'], 'فیلدهای ضروری را خالی نگذارید.');
                if (!file_exists($values['image'])) {
                    $form->setError('تصویر انتخاب شده، نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {

                $res = $model->insert_it('sliders', [
                    'image' => trim($values['image']),
                    'link' => trim($values['link']),
                    'priority' => trim($values['priority']),
                    'status' => $form->isChecked('status') ? 1 : 0
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
                $this->data['sldVals'] = $form->getValues();
            }
        }

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'افزودن اسلایدر');

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        $this->_render_page([
            'pages/be/Slider/addSlider',
            'templates/be/efm'
        ]);
    }

    public function manageSliderAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['sliders'] = $model->select_it(null, 'sliders');

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'مشاهده اسلاید‌ها');

        $this->data['js'][] = $this->asset->script('be/js/admin.main.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/media/fancybox.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/datatables.min.js');
        $this->data['js'][] = $this->asset->script('be/js/plugins/tables/datatables/numeric-comma.min.js');
        $this->data['js'][] = $this->asset->script('be/js/pages/datatables_advanced.js');

        $this->_render_page('pages/be/Slider/manageSlider');
    }

    public function editSliderAction($param)
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();

        if (!isset($param[0]) || !is_numeric($param[0]) || !$model->is_exist('guarantees', 'id=:id', ['id' => $param[0]])) {
            $this->redirect(base_url('admin/manageSlider'));
        }

        $this->data['param'] = $param;

        $this->data['errors'] = [];
        $this->data['sldVals'] = [];

        $this->data['sldVals'] = $model->select_it(null, 'sliders', ['link'], 'id=:id', ['id' => $param[0]])[0];

        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token'] = $form->csrfToken('editGuarantee');
        $form->setFieldsName(['image', 'link', 'priority', 'status'])
            ->setDefaults('status', 'off')
            ->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {
                $form->isRequired(['image', 'link'], 'فیلدهای ضروری را خالی نگذارید.');

                if (!file_exists($values['image'])) {
                    $form->setError('تصویر انتخاب شده، نامعتبر است.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->update_it('sliders', [
                    'image' => trim($values['image']),
                    'link' => trim($values['link']),
                    'priority' => trim($values['priority']),
                    'status' => $form->isChecked('status') ? 1 : 0
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

        $this->data['sldVals'] = $model->select_it(null, 'sliders', '*', 'id=:id', ['id' => $param[0]])[0];

        // Base configuration
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'ویرایش اسلاید', $this->data['sldVals']['id']);

        $this->load->helper('easy file manager');
        //Security options
        $this->data['upload']['allow_upload'] = allow_upload(false);
        $this->data['upload']['allow_create_folder'] = allow_create_folder(false);
        $this->data['upload']['allow_direct_link'] = allow_direct_link();
        $this->data['upload']['MAX_UPLOAD_SIZE'] = max_upload_size();

        // Extra css
        $this->data['css'][] = $this->asset->css('be/css/efm.css');

        // Extra js
        $this->data['js'][] = $this->asset->script('be/js/pick.file.js');

        $this->_render_page([
            'pages/be/Slider/editSlider',
            'templates/be/efm'
        ]);
    }

    public function deleteSliderAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 403, 'دسترسی غیر مجاز');
        }

        $model = new Model();

        $id = @$_POST['postedId'];
        $table = 'sliders';
        if (!isset($id)) {
            message('error', 200, 'اسلاید نامعتبر است.');
        }
        if (!$model->is_exist($table, 'id=:id', ['id' => $id])) {
            message('error', 200, 'اسلاید وجود ندارد.');
        }

        $res = $model->delete_it($table, 'id=:id', ['id' => $id, 'del' => 1]);
        if ($res) {
            message('success', 200, 'اسلاید با موفقیت حذف شد.');
        }

        message('error', 200, 'عملیات با خطا مواجه شد.');
    }

    public function manageContactUsAction()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect(base_url('admin/login'));
        }

        $model = new Model();
        $this->data['cusVals'] = $model->select_it(null, 'contact_us', '*', null, null, null, ['send_time DESC']);

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

        $model = new Model();
        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name', 'level'],
            'level!=:lvl', ['lvl' => 0], null, 'level ASC');

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
            'imgTop', 'imgTopLink', 'imgNext', 'imgNextLink', 'imgSide', 'imgSideLink', 'imgFourImages',
            'imgFourImagesLink', 'imgTwoImages', 'imgTwoImagesLink', 'imgOneImage', 'imgOneImageLink',
            'sliderTitle', 'sliderViewAll', 'sliderCategory', 'sliderItemsCount', 'showBrands'
        ])->setDefaults('showBrands', 'off')
            ->setDefaults('sliderCategory', [])->setMethod('post', [], ['showBrands']);
        try {
            $formImages->beforeCheckCallback(function (&$values) use ($formImages) {
                if ($values['imgTop'] != '' && !file_exists($values['imgTop'])) {
//                    $formImages->setError('تصویر انتخاب شده برای بالای اسلایدر اصلی، نامعتبر است.');
                    $values['imgTop'] = '';
                }
                if ($values['imgNext'] != '' && !file_exists($values['imgNext'])) {
//                    $formImages->setError('تصویر انتخاب شده برای کنار اسلایدر اصلی، نامعتبر است.');
                    $values['imgNext'] = '';
                }
                $values['imgSide'] = array_filter($values['imgSide'], function ($value, $key) use ($values) {
                    $res = $value != '' && file_exists($value);
                    if ($res) {
                        unset($values['imgSideLink'][$key]);
                        return $res;
                    }
                    return false;
                }, ARRAY_FILTER_USE_BOTH);

                $values['imgFourImages'] = array_filter($values['imgFourImages'], function ($value) {
                    return $value == '' || file_exists($value);
                });
                $values['imgTwoImages'] = array_filter($values['imgTwoImages'], function ($value) {
                    return $value == '' || file_exists($value);
                });
                if ($values['imgOneImage'] != '' && !file_exists($values['imgOneImage'])) {
//                    $formImages->setError('تصویر انتخاب شده برای تصویر تکی، نامعتبر است.');
                    $values['imgOneImage'] = '';
                }

                $values['sliderCategory'] = array_map(function ($value) {
                    $catCols = array_column($this->data['categories'], 'id');
                    if (in_array($value, $catCols) === false) return -1;
                    return $value;
                }, $values['sliderCategory']);
                $values['sliderItemsCount'] = array_map(function ($value) {
                    if ($value < 10 || $value > 30) return 10;
                    return $value;
                }, $values['sliderItemsCount']);
            })->afterCheckCallback(function ($values) use ($formImages) {
                $this->data['setting']['pages']['index']['showBrands'] = $formImages->isChecked('showBrands') ? 1 : 0;

                $this->data['setting']['pages']['index']['topImage']['image'] = $values['imgTop'];
                $this->data['setting']['pages']['index']['topImage']['link'] = $values['imgTopLink'];

                $this->data['setting']['pages']['index']['nextToSliderImage']['image'] = $values['imgNext'];
                $this->data['setting']['pages']['index']['nextToSliderImage']['link'] = $values['imgNextLink'];

                $this->data['setting']['pages']['index']['sideImages']['images'] = $values['imgSide'];
                $this->data['setting']['pages']['index']['sideImages']['links'] = $values['imgSideLink'];;

                $this->data['setting']['pages']['index']['fourImages']['images'] = $values['imgFourImages'];
                $this->data['setting']['pages']['index']['fourImages']['links'] = $values['imgFourImagesLink'];

                $this->data['setting']['pages']['index']['twoImages']['images'] = $values['imgTwoImages'];
                $this->data['setting']['pages']['index']['twoImages']['links'] = $values['imgTwoImagesLink'];

                $this->data['setting']['pages']['index']['oneImage']['image'] = $values['imgOneImage'];
                $this->data['setting']['pages']['index']['oneImage']['link'] = $values['imgOneImageLink'];

                $this->data['setting']['pages']['index']['sliders']['title'] = $values['sliderTitle'];
                $this->data['setting']['pages']['index']['sliders']['viewAllLink'] = $values['sliderViewAll'];
                $this->data['setting']['pages']['index']['sliders']['category'] = $values['sliderCategory'];
                $this->data['setting']['pages']['index']['sliders']['count'] = $values['sliderItemsCount'];

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

        // Footer panel setting form submit
        $form = new Form();
        $this->data['errors_footer'] = [];
        $this->data['form_token_footer'] = $form->csrfToken('settingFooter');
        $form->setFieldsName(['footer_1_title', 'footer_1_text', 'footer_1_link', 'instagram',
            'information_1', 'information_2', 'information_3', 'descTitle', 'desc', 'namad_1', 'namad_2'
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

                $this->data['setting']['footer']['footer_1']['sections']['section_1']['title'] = $values['footer_1_title'][0];
                $this->data['setting']['footer']['footer_1']['sections']['section_1']['links'] = $sec1;

                $this->data['setting']['footer']['footer_1']['sections']['section_2']['title'] = $values['footer_1_title'][1];
                $this->data['setting']['footer']['footer_1']['sections']['section_2']['links'] = $sec2;

                $this->data['setting']['footer']['footer_1']['sections']['section_3']['title'] = $values['footer_1_title'][2];
                $this->data['setting']['footer']['footer_1']['sections']['section_3']['links'] = $sec3;

                $this->data['setting']['footer']['footer_1']['socials']['instagram'] = $values['instagram'];

                $this->data['setting']['footer']['footer_2']['section_1'] = $values['information_1'];
                $this->data['setting']['footer']['footer_2']['section_2'] = $values['information_2'];
                $this->data['setting']['footer']['footer_2']['section_3'] = $values['information_3'];

                $this->data['setting']['footer']['footer_3']['description']['title'] = $values['descTitle'];
                $this->data['setting']['footer']['footer_3']['description']['description'] = $values['desc'];
                $this->data['setting']['footer']['footer_3']['namad']['namad_1'] = $values['namad_1'];
                $this->data['setting']['footer']['footer_3']['namad']['namad_2'] = $values['namad_2'];

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

        // Footer panel setting form submit
        $formAdmin = new Form();
        $this->data['errors_admin'] = [];
        $this->data['form_token_admin'] = $formAdmin->csrfToken('settingAdmin');
        $formAdmin->setFieldsName(['offerReaction', 'offerActivationReaction'])
            ->setMethod('post');
        try {
            $formAdmin->afterCheckCallback(function (&$values) use ($formAdmin) {
                if (!in_array($values['offerReaction'], $this->offerReactionParams)) {
                    $values['offerReaction'] = self::OFFER_REACTION_SHOW_ERROR;
                }
                if (!in_array($values['offerActivationReaction'], $this->offerActivationReactionParams)) {
                    $values['offerActivationReaction'] = self::OFFER_ACTIVATION_REACTION_SHOW_ERROR;
                }

                $this->data['setting']['admin']['panel']['offer_reaction'] = $values['offerReaction'];
                $this->data['setting']['admin']['panel']['offer_activation_reaction'] = $values['offerActivationReaction'];

                $this->setting = array_merge_recursive_distinct($this->setting, $this->data['setting']);
                $res = write_json(CORE_PATH . 'config.json', $this->setting);

                if (!$res) {
                    $formAdmin->setError('خطا در انجام عملیات!');
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $formAdmin->checkForm()->isSuccess();
        if ($formAdmin->isSubmit()) {
            if ($res) {
                $this->data['success_admin'] = 'عملیات با موفقیت انجام شد.';
            } else {
                $this->data['errors_admin'] = $formAdmin->getError();
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
