<?php

use HForm\Form;
use HPayment\Payment;
use HPayment\PaymentClasses\PaymentZarinPal;
use HPayment\PaymentException;
use HPayment\PaymentFactory;

include_once 'AbstractController.class.php';

class UserController extends AbstractController
{
    public function dashboardAction()
    {
        $this->_checker();
        //-----
        $user = new UserModel();
        $this->data['payedEvents'] = $user->getPayedEvents(['user_id' => $this->data['identity']->id]);

        // Save information
        $this->_saveInformation();
        // Change password
        $this->_passwordChange();

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'داشبورد');

        // Extra js
        $this->data['js'][] = $this->asset->script('fe/js/userDashboardJs.js');

        $this->_render_page([
            'pages/fe/user-profile',
        ]);
    }

    public function eventAction($param)
    {
        $this->_checker();
        //-----
        if (!isset($param[0]) || !isset($param[1]) && $param[0] != 'detail') {
            $_SESSION['user-event'] = 'پارامترهای ورودی نامعتبر برای جزئیات طرح';
            $this->redirect(base_url('user/dashboard'));
        }
        //-----
        $this->data['param'] = $param;
        //-----
        $user = new UserModel();
        $model = new Model();
        $this->data['event'] = $user->getEventDetail(['slug' => $param[1], 'user_id' => $this->data['identity']->id]);
        $this->data['event']['payments'] = [];
        if (count($this->data['event'])) {
            $this->data['event']['options'] = json_decode($this->data['event']['options'], true);
            $this->data['event']['payments'] = $model->select_it(null, self::PAYMENT_TABLE_ZARINPAL, '*',
                'user_id=:uId AND plan_id=:pId', ['uId' => $this->data['identity']->id, 'pId' => $this->data['event']['id']]);
        }
        // If don't have any event for current user, redirect him/her to dashboard to make better decision
        $model = new Model();
        if (!$model->is_exist('plans', 'slug=:slug', ['slug' => $param[1]]) || !count($this->data['event'])) {
            $_SESSION['user-event'] = 'جزئیاتی برای طرح درخواست شده وجود ندارد';
            $this->redirect(base_url('user/dashboard'));
        }
        // Payment form
        $this->_zarinpalPayment();
        // Export ticket
        $this->_exportTicket();

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جزئیات طرح', $this->data['event']['title']);

        $this->_render_page([
            'pages/fe/user-event-detail',
        ]);
    }

    public function paymentResultAction()
    {
        $this->_checker();
        //-----
        $this->_zarinpalCheck();

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'نتیجه پرداخت');

        $this->_render_page([
            'pages/fe/payment-result',
        ]);
    }

    public function ajaxUploadUserImageAction()
    {
        if (!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 200, 'دسترسی غیر مجاز');
        }
        if (empty($_FILES['file'])) {
            message('error', 200, 'پارامترهای وارد شده نامعتبر است.');
        }

        $userDir = PROFILE_IMAGE_DIR;
        //
        if (!file_exists($userDir)) {
            mkdir($userDir, 0777, true);
        }
        //
        $imageExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $image = PROFILE_IMAGE_DIR . $this->data['identity']->username . '.' . $imageExt;
        $model = new Model();
        $model->transactionBegin();
        $res = $model->update_it('users', ['image' => $image], 'id=:id', ['id' => $this->data['identity']->id]);
        //
        if ($res) {
            $this->load->library('Upload/vendor/autoload');
            $storage = new \Upload\Storage\FileSystem($userDir, true);
            $file = new \Upload\File('file', $storage);

            // Set file name to user's phone number
            $file->setName($this->data['identity']->username);

            // Validate file upload
            // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
            $file->addValidations(array(
                // Ensure file is of type "image/png"
                new \Upload\Validation\Mimetype(['image/png', 'image/jpg', 'image/jpeg', 'image/gif']),

                // Ensure file is no larger than 4M (use "B", "K", M", or "G")
                new \Upload\Validation\Size('4M')
            ));

            // Try to upload file
            try {
                // Success!
                $res = $file->upload();
            } catch (\Exception $e) {
                // Fail!
                $res = false;
            }
            //
            if ($res) {
                $this->auth->storeIdentity([
                    'image' => $image,
                ]);
                $this->data['identity'] = $this->auth->getIdentity();
                $model->transactionComplete();
                message('success', 200, ['تصویر با موفقیت بروزرسانی شد.', $image]);
            }
        }
        $model->transactionRollback();
        message('error', 200, 'خطا در بروزرسانی تصویر');
    }

    //-----

    public function informationCompletionAction()
    {
        $this->_checker();
        //-----
        $this->_saveInformation();
        //-----
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'فرم اطلاعات');

        // Extra js
        $this->data['js'][] = $this->asset->script('fe/js/userDashboardJs.js');

        $this->_render_page([
            'pages/fe/user-information',
        ]);
    }

    //-----

    protected function _saveInformation()
    {
        if (!$this->_checker(true)) return;
        //-----
        $model = new Model();
        $this->data['informationErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_information'] = $form->csrfToken('saveInformation');
        $formFields = ['full-name', 'father-name', 'n-code', 'id-code', 'id-location', 'grade', 'gender', 'home-phone',
            'e-phone', 'illness', 'illness-desc', 'allergy', 'allergy-desc', 'province', 'city', 'postal-code', 'address'];
        if (!in_array($this->data['identity']->role_id, [AUTH_ROLE_COLLEGE_STUDENT, AUTH_ROLE_GRADUATE])) {
            $formFields = array_merge(['school', 'point', 'degree'], $formFields);
        }
        if ($this->data['identity']->role_id != AUTH_ROLE_STUDENT) {
            $formFields = array_merge(['soldiery', 'soldiery-place', 'soldiery-end', 'marriage', 'children'], $formFields);
        }
        $form->setFieldsName($formFields)
            ->setDefaults(['grade', 'illness', 'allergy', 'soldiery', 'marriage'], 0)
            ->setDefaults(['illness-desc', 'allergy-desc'], '')
            ->setMethod('post', [], ['illness', 'allergy', 'marriage']);
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form, $formFields) {
                $form->isRequired([
                    'full-name', 'n-code', 'e-phone', 'gender', 'grade', 'illness', 'allergy'
                ], 'فیلدهای اجباری را خالی نگذارید.');
                $form->validatePersianName('full-name', 'نام و نام خانوادگی باید فقط حروف باشد.')
                    ->validateNationalCode('n-code')
                    ->isIn('grade', array_keys(EDU_GRADES), 'وضعیت تحصیلی انتخاب شده نامعتبر است.')
                    ->isIn('gender', [GENDER_MALE, GENDER_FEMALE], 'جنسیت انتخاب شده نامعتبر است.')
                    ->validate('numeric', 'e-phone', 'شماره تلفن رابط باید عدد باشد.')
                    ->isIn('illness', [1, 2], 'فیلد دارا بودن به بیماری نامعتبر است.')
                    ->isIn('allergy', [1, 2], 'فیلد دارا بودن به حساسیت نامعتبر است.');
                //-----
                if (!empty(trim($values['id-code']))) {
                    $form->validate('numeric', 'id-code', 'شماره شناسنامه باید عدد باشد.');
                }
                if (!empty(trim($values['home-phone']))) {
                    $form->validate('numeric', 'home-phone', 'شماره تلفن منزل باید عدد باشد.');
                }
                if (!empty(trim($values['father-name']))) {
                    $form->validatePersianName('father-name', 'نام پدر باید فقط حروف باشد.');
                }
                if (!empty(trim($values['id-location']))) {
                    $form->validatePersianName('id-location', 'محل صدور شناسنامه باید فقط حروف باشد.');
                }
                if (!empty(trim($values['province']))) {
                    $form->validatePersianName('province', 'استان محل سکونت باید فقط حروف باشد.');
                }
                if (!empty(trim($values['city']))) {
                    $form->validatePersianName('city', 'شهر محل سکونت باید فقط حروف باشد.');
                }
                //-----
                if ($form->isChecked('illness', 1)) {
                    if (empty(trim($values['illness-desc']))) {
                        $form->setError('توضیحات در مورد دارا بودن بیماری وارد نشده است.');
                    }
                }
                if ($form->isChecked('allergy', 1)) {
                    if (empty(trim($values['allergy-desc']))) {
                        $form->setError('توضیحات در مورد دارا بودن به حساسیت وارد نشده است.');
                    }
                }
                //-----
                if (!in_array($this->data['identity']->role_id, [AUTH_ROLE_COLLEGE_STUDENT, AUTH_ROLE_GRADUATE])) {
                    $form->isRequired(['school'], 'فیلدهای اجباری را خالی نگذارید.');
                    $form->isIn('degree', array_merge([0], array_keys(EDU_FIELDS)), 'رشته تحصیلی انتخاب شده نامعتبر است.');
                    if (!empty(trim($values['school']))) {
                        $form->validatePersianName('school', 'مدرسه محل تحصیل باید فقط حروف باشد.');
                    }
                }
                //-----
                if ($this->data['identity']->role_id != AUTH_ROLE_STUDENT) {
                    $form->isIn('soldiery', [0, 1, 2, 3, 4], 'وضعیت سربازی انتخاب شده نامعتبر است.')
                        ->isIn('marriage', [MARRIAGE_MARRIED, MARRIAGE_SINGLE, MARRIAGE_DEAD], 'وضعیت تأهل انتخاب شده نامعتبر است.');
                    if (!empty(trim($values['soldiery-place']))) {
                        $form->validate('numeric', 'soldiery-place', 'سال پایان خدمت باید شامل ۴ عدد باشد.')
                            ->isLengthEquals('soldiery-place', 4, 'سال پایان خدمت باید شامل ۴ عدد باشد.');
                    }
                    if (!empty(trim($values['children']))) {
                        $form->validate('numeric', 'children', 'تعداد فرزند باید عدد باشد.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $updateFields = [
                    'full_name' => trim($values['full-name']),
                    'father_name' => trim($values['father-name']),
                    'phone' => convertNumbersToPersian(trim($values['home-phone']), true),
                    'connector_phone' => convertNumbersToPersian(trim($values['e-phone']), true),
                    'province' => trim($values['province']),
                    'city' => trim($values['city']),
                    'address' => trim($values['address']),
                    'postal_code' => convertNumbersToPersian(trim($values['postal-code']), true),
                    'n_code' => convertNumbersToPersian(trim($values['n-code']), true),
                    'id_code' => convertNumbersToPersian(trim($values['id-code']), true),
                    'birth_certificate_place' => trim($values['id-location']),
                    'gender' => convertNumbersToPersian(trim($values['gender']), true),
                    'grade' => convertNumbersToPersian(trim($values['grade']), true),
                    'illness' => convertNumbersToPersian(trim($values['illness']), true),
                    'illness_desc' => trim($values['illness-desc']),
                    'allergy' => convertNumbersToPersian(trim($values['allergy']), true),
                    'allergy_desc' => trim($values['allergy-desc']),
                ];
                //-----
                if (!in_array($this->data['identity']->role_id, [AUTH_ROLE_COLLEGE_STUDENT, AUTH_ROLE_GRADUATE])) {
                    $updateFields['school'] = trim($values['school']);
                    $updateFields['field'] = convertNumbersToPersian(trim($values['degree']), true);
                    $updateFields['gpa'] = convertNumbersToPersian(trim($values['point']), true);
                }
                //-----
                if ($this->data['identity']->role_id != AUTH_ROLE_STUDENT) {
                    $updateFields['military_status'] = convertNumbersToPersian(trim($values['soldiery']), true);
                    $updateFields['military_place'] = trim($values['soldiery-place']);
                    $updateFields['military_end_year'] = convertNumbersToPersian(trim($values['soldiery-end']), true);
                    $updateFields['marital_status'] = convertNumbersToPersian(trim($values['marriage']), true);
                    $updateFields['children_count'] = convertNumbersToPersian(trim($values['children']), true);
                }

                $model->transactionBegin();
                $res = $model->update_it('users', $updateFields, 'id=:id', ['id' => $this->data['identity']->id]);
                if ($res) {
                    $infoFlag = 0;
                    if (!empty($this->data['identity']->full_name) && !empty($this->data['identity']->connector_phone) &&
                        !empty($this->data['identity']->n_code) && !empty($this->data['identity']->gender) &&
                        !empty($this->data['identity']->grade)) {
                        if (!in_array($this->data['identity']->role_id, [AUTH_ROLE_COLLEGE_STUDENT, AUTH_ROLE_GRADUATE])) {
                            if (!empty($this->data['identity']->school)) {
                                $infoFlag = 1;
                            }
                        } else {
                            $infoFlag = 1;
                        }
                    }

                    $this->auth->storeIdentity(array_merge($updateFields, ['info_flag' => $infoFlag]));
                    $this->data['identity'] = $this->auth->getIdentity();
                    $res2 = $model->update_it('users', [
                        'info_flag' => $infoFlag
                    ], 'id=:id', ['id' => $this->data['identity']->id]);
                    if ($res2) {
                        $model->transactionComplete();
                    } else {
                        $model->transactionRollback();
                        $form->setError('خطا در انجام عملیات!');
                    }
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
                if (isset($_GET['back_url'])) {
                    $this->redirect($_GET['back_url'], 'اطلاعات شما بروزرسانی شد.', 1);
                }
                $this->data['informationSuccess'] = 'اطلاعات شما بروزرسانی شد.';
            } else {
                $this->data['informationErrors'] = $form->getError();
            }
        }
    }

    protected function _passwordChange()
    {
        if (!$this->_checker(true)) return;
        //-----
        $model = new Model();
        $this->data['passwordErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_password'] = $form->csrfToken('changePassword');
        $formFields = ['last-password', 'new-password', 'new-re-password'];
        if (!$this->auth->isInAdminRole($this->data['identity']->role_id)) {
            $formFields = array_merge(['role'], $formFields);
        }
        $form->setFieldsName($formFields)->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form, $formFields) {
                $form->isRequired($formFields, 'فیلدهای اجباری را خالی نگذارید.');
                if (isset($_POST['role'])) {
                    if ($this->auth->isInAdminRole($this->data['identity']->role_id)) {
                        $form->setError('نقش شما در این قسمت قابل تغییر نمی‌باشد، لطفا تلاش نفرمایید!');
                    } elseif (!in_array($_POST['role'], [AUTH_ROLE_STUDENT, AUTH_ROLE_COLLEGE_STUDENT, AUTH_ROLE_GRADUATE])) {
                        $form->setError('نقش انتخاب شده نامعتبر است.');
                    }
                }
                if (!count($form->getError())) {
                    if (password_verify($values['last-password'], $this->data['identity']->password)) {
                        $form->isLengthInRange('new-password', 8, 16, 'پسورد باید حداقل ۸ و حداکثر ۱۶ رقم باشد.');
                        $form->validatePassword('new-password', 2, 'پسورد باید شامل حروف و اعداد انگلیسی باشد.');
                        if ($values['new-password'] != $values['new-re-password']) {
                            $form->setError('رمز عبور با تکرار آن مغایرت دارد.');
                        }
                    } else {
                        $form->setError('رمز عبور قبلی اشتباه است! لطفا دوباره تلاش نمایید.');
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $model->transactionBegin();
                $res = $model->update_it('users', [
                    'password' => password_hash($values['new-password'], PASSWORD_DEFAULT),
                ], 'id=:id', ['id' => $this->data['identity']->id]);

                $res2 = true;
                $res3 = true;
                if (isset($_POST['role'])) {
                    $res2 = $model->update_it('users_roles', [
                        'role_id' => $_POST['role']
                    ], 'user_id=:uId', ['uId' => $this->data['identity']->id]);
                    $res3 = $model->update_it('users', [
                        'info_flag' => 0
                    ], 'id=:id', ['id' => $this->data['identity']->id]);
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
                $this->redirect(base_url('logout?back_url=' . base_url('index#login_modal')), 'رمز عبور با موفقیت تغییر یافت، با رمز عبور جدید وارد شوید.', 1);
            } else {
                $this->data['passwordErrors'] = $form->getError();
            }
        }
    }

    //-----

    protected function _exportTicket()
    {
        if (!$this->_checker(true)) return;
        //-----
        $model = new Model();
        $this->data['ticketErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_ticket'] = $form->csrfToken('ticketToken');
        $form->setFieldsName(['ticket'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form) {

            })->afterCheckCallback(function ($values) use ($model, $form) {
//                $res = $model->update_it('users', [
//                    'password' => password_hash($values['new-password'], PASSWORD_DEFAULT),
//                ], 'id=:id', ['id' => $this->data['identity']->id]);

//                if (!$res) {
//                $form->setError('خطا در انجام عملیات!');
                $form->setError('این امکان هنوز فعال نشده است!');
//                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $form->checkForm()->isSuccess();
        if ($form->isSubmit()) {
            if ($res) {

            } else {
                $this->data['ticketErrors'] = $form->getError();
            }
        }
    }

    protected function _zarinpalPayment()
    {
        if (!$this->_checker(true)) return;
        //-----
        $model = new Model();
        $this->data['paymentErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_payment'] = $form->csrfToken('paymentToken');
        $form->setFieldsName(['pay'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                if (!$model->is_exist('block_list', 'n_code=:code', ['code' => $this->data['identity']->n_code])) {
                    if ($this->data['identity']->info_flag == 0) {
                        $_SESSION['event-eventSubmit'] = 'برای پرداخت ابتدا فیلدهای اجباری را تکمیل کنید.';
                        $this->redirect(base_url('user/informationCompletion?back_url=' . base_url('user/event/detail/' . $this->data['event']['slug'])));
                    }
                    $values['remained'] = convertNumbersToPersian((int)$this->data['event']['total_amount'], true) - convertNumbersToPersian((int)$this->data['event']['payed_amount'], true);
                    $values['maxRange'] = range(1, (int)((int)$values['remained'] / (int)$this->data['event']['min_price']));
                    $form->isIn('pay', $values['maxRange'], 'مبلغ انتخاب شده نامعتبر است.');
                    if ((time() - (24 * 60 * 60)) >= $this->data['event']['start_at']) {
                        $form->setError('تاریخ برای پرداخت طرح تمام شده است.');
                    }
                } else {
                    $form->setError('شما قادر به ثبت نام در این طرح نمی‌باشید.');
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $price = (int)$values['remained'] > ((int)$this->data['event']['min_price'] * (int)$values['pay']) ?
                    ((int)$this->data['event']['min_price'] * $values['pay']) :
                    (int)$values['remained'];

                $res = $this->_zarinpalConnection([
                    'price' => $price,
                    'desc' => $this->data['event']['title'],
                    'plan_id' => $this->data['event']['id'],
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

            } else {
                $this->data['paymentErrors'] = $form->getError();
            }
        }
    }

    protected function _zarinpalConnection($parameters)
    {
        if (!$this->_checker(true)) return false;
        //-----
        $this->load->library('HPayment/vendor/autoload');
        try {
            $model = new Model();
            $zarinpal = PaymentFactory::get_instance(PaymentFactory::BANK_TYPE_ZARINPAL);
            //-----
            $redirectMessage = 'انتقال به درگاه پرداخت ...';
            $wait = 1;
            //-----
            $payRes = $zarinpal->create_request([
                'Amount' => $parameters['price'],
                'Description' => $parameters['desc'] ?? '',
                'CallbackURL' => base_url('user/paymentResult')
            ])->get_result();
            if ($payRes->Status == Payment::PAYMENT_STATUS_OK_ZARINPAL) {
                // Insert new payment in DB
                $res = $model->insert_it(self::PAYMENT_TABLE_ZARINPAL, [
                    'authority' => 'zarinpal-' . $payRes->Authority,
                    'user_id' => $this->data['identity']->id,
                    'plan_id' => $parameters['plan_id'],
                    'amount' => $parameters['price'],
                ]);

                if ($res) {
                    // Send user to zarinpal for transaction
                    $this->redirect($zarinpal->urls[Payment::PAYMENT_URL_PAYMENT_ZARINPAL] . $payRes->Authority, $redirectMessage, $wait);
                    return true;
                } else {
                    return false;
                }
            } else {
//                $error = $zarinpal->get_message($payRes->Status);
                return false;
            }
        } catch (PaymentException $e) {
//            $error = $e->__toString();
            return false;
        }
    }

    protected function _zarinpalCheck()
    {
        $this->load->library('HPayment/vendor/autoload');
        try {
            $model = new Model();
            $zarinpal = PaymentFactory::get_instance(PaymentFactory::BANK_TYPE_ZARINPAL);
            $getVars = $zarinpal->handle_request()->get_result();
            if (!isset($getVars[Payment::PAYMENT_RETURNED_AUTHORITY_ZARINPAL]) || !isset($getVars[Payment::PAYMENT_RETURNED_STATUS_ZARINPAL])) {
                $this->data['error'] = 'تراکنش نامعتبر است!';
                $this->data['is_success'] = false;
                $this->data['have_ref_id'] = false;
                return;
            }

            $authority = $getVars[Payment::PAYMENT_RETURNED_AUTHORITY_ZARINPAL];
            // Get payment with current authority
            $curPay = $model->select_it(null, self::PAYMENT_TABLE_ZARINPAL, '*',
                'authority=:auth', ['auth' => 'zarinpal-' . $authority]);

            if (count($curPay)) {
                $curPay = $curPay[0];
                $curFactor = $model->select_it(null, 'factors', '*',
                    'user_id=:uId AND plan_id=:pId', ['uId' => $curPay['user_id'], 'pId' => $curPay['plan_id']]);
                if (count($curFactor)) {
                    $curFactor = $curFactor[0];
                    // Set factor_code to global data
                    $this->data['factor_code'] = $curFactor['factor_code'];
                    if ($curPay['payment_status'] != Payment::PAYMENT_TRANSACTION_SUCCESS_ZARINPAL) {
                        $res = $zarinpal->verify_request($curPay['amount']);
                        if (intval($zarinpal->status) == Payment::PAYMENT_TRANSACTION_SUCCESS_ZARINPAL || // Successful transaction
                            intval($zarinpal->status) == Payment::PAYMENT_TRANSACTION_DUPLICATE_ZARINPAL) { // Duplicated transaction
                            $this->data['is_success'] = true;
                            $this->data['have_ref_id'] = true;

                            if (intval($zarinpal->status) == Payment::PAYMENT_TRANSACTION_SUCCESS_ZARINPAL) {
                                $this->data['ref_id'] = $res->RefID;

                                // Update payment status and refID for success
                                $model->update_it(self::PAYMENT_TABLE_ZARINPAL, [
                                    'payment_code' => $this->data['ref_id'],
                                    'payment_status' => $zarinpal->status,
                                ], 'authority=:auth', ['auth' => 'zarinpal-' . $authority]);
                                $model->update_it('factors', [],
                                    'user_id=:uId AND plan_id=:pId', ['uId' => $curPay['user_id'], ['pId' => $curPay['plan_id']]], [
                                        'payed_amount' => !empty($curFactor['payed_amount']) ? 'payed_amount+' . (int)$curPay['amount'] : (int)$curPay['amount']
                                    ]);
                            }
                        } else if (intval($zarinpal->status) == Payment::PAYMENT_TRANSACTION_CANCELED_ZARINPAL) { // Transaction was canceled
                            $this->data['is_success'] = false;
                            $this->data['have_ref_id'] = false;
                            $this->data['error'] = $res;
                        } else { // Failed transaction
                            $this->data['is_success'] = false;
                            $this->data['have_ref_id'] = true;
                            $this->data['ref_id'] = $res->RefID;

                            // Update payment status and refID for fail
                            $model->update_it(self::PAYMENT_TABLE_ZARINPAL, [
                                'payment_code' => $this->data['ref_id'],
                                'payment_status' => $zarinpal->status,
                            ], 'authority=:auth', ['auth' => 'zarinpal-' . $authority]);
                            $this->data['error'] = $zarinpal->get_message($zarinpal->status);
                        }

                        // Update time anyway
                        $model->update_it(self::PAYMENT_TABLE_ZARINPAL, [
                            'payment_date' => time(),
                        ], 'authority=:auth', ['auth' => 'zarinpal-' . $authority]);
                        $this->data['error'] = $zarinpal->get_message($zarinpal->status);
                    } else {
                        $this->data['is_success'] = true;
                        $this->data['have_ref_id'] = true;
                        $this->data['ref_id'] = $curPay['payment_code'];
                    }
                } else {
                    $this->data['error'] = 'تراکنش نامعتبر است!';
                    $this->data['is_success'] = false;
                    $this->data['have_ref_id'] = false;
                }
            } else {
                $this->data['error'] = 'تراکنش نامعتبر است!';
                $this->data['is_success'] = false;
                $this->data['have_ref_id'] = false;
            }
        } catch (PaymentException $e) {
            die($e);
        }
    }

    //-----

    protected function _checker($returnBoolean = false)
    {
        if (!$this->auth->isLoggedIn()) {
            if ((bool)$returnBoolean) return false;
            $this->error->show_404();
        }

        return true;
    }
}