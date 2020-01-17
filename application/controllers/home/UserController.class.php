<?php

use HForm\Form;

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

    public function informationAction()
    {
        $this->_checker();
        //-----
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'فرم اطلاعات');

        $this->_render_page([
            'pages/fe/user-information',
        ]);
    }

    public function eventAction($param)
    {
        $this->_checker();
        //-----
        if (!isset($param[0]) || !isset($param[1]) && $param[0] != 'detail') {
            $_SESSION['user-event'] = 'پارامترهای ورودی نامعتبر برای جزئیات طرح';
            $this->redirect('user/dashboard');
        }
        //-----
        $user = new UserModel();
        $this->data['event'] = $user->getEventDetail(['slug' => $param[1], 'user_id' => $this->data['identity']->id]);

        // If don't have any event for current user, redirect him/her to dashboard to make better decision
        $model = new Model();
        if (!$model->is_exist('plans', 'slug=:slug', ['slug' => $param[1]]) || !count($this->data['event'])) {
            $_SESSION['user-event'] = 'جزئیاتی برای طرح درخواست شده وجود ندارد';
            $this->redirect('user/dashboard');
        }

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جزئیات طرح', $this->data['event']['title']);

        $this->_render_page([
            'pages/fe/user-event-detail',
        ]);
    }

    public function ajaxUploadUserImageAction()
    {
        if(!$this->auth->isLoggedIn() || !is_ajax()) {
            message('error', 200, 'دسترسی غیر مجاز');
        }
        if(empty($_FILES['file'])) {
            message('error', 200, 'پارامترهای وارد شده نامعتبر است.');
        }

        $userDir = PROFILE_IMAGE_DIR;
        //
        if (!file_exists($userDir)) {
            mkdir($userDir, 0777, true);
        }
        //
        $imageExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        //
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
        if($res) {
            $image = PROFILE_IMAGE_DIR . $this->data['identity']->username . '.' . $imageExt;
            $this->auth->storeIdentity([
                'image' => $image,
            ]);
            message('success', 200, ['تصویر با موفقیت بروزرسانی شد.', $image]);
        }
        message('error', 200, 'خطا در بروزرسانی تصویر');
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
            'e-phone', 'province', 'city', 'postal-code', 'address'];
        if (!in_array($this->data['identity']->role_id, [AUTH_ROLE_COLLEGE_STUDENT, AUTH_ROLE_GRADUATE])) {
            $formFields = array_merge(['school', 'point', 'degree'], $formFields);
        }
        if ($this->data['identity']->role_id != AUTH_ROLE_STUDENT) {
            $formFields = array_merge(['soldiery', 'soldiery-place', 'soldiery-end', 'marriage', 'children'], $formFields);
        }
        $form->setFieldsName($formFields)->setMethod('post');
        try {
            $form->beforeCheckCallback(function () use ($model, $form, $formFields) {
                $form->isRequired($formFields, 'فیلدهای اجباری را خالی نگذارید.');
                $form->validateUsername('full-name', 'نام و نام خانوادگی باید فقط حروف باشد.')
                    ->validateUsername('father-name', 'نام پدر باید فقط حروف باشد.')
                    ->validateNationalCode('n-code')
                    ->validate('numeric', 'id-code', 'شماره شناسنامه باید عدد باشد.')
                    ->validateUsername('id-location', 'محل صدور شناسنامه باید فقط حروف باشد.')
                    ->isIn('grade', array_keys(EDU_GRADES), 'وضعیت تحصیلی انتخاب شده نامعتبر است.')
                    ->isIn('gender', [GENDER_MALE, GENDER_FEMALE], 'جنسیت انتخاب شده نامعتبر است.')
                    ->validate('numeric', 'home-phone', 'شماره تلفن منزل باید عدد باشد.')
                    ->validate('numeric', 'e-phone', 'شماره تلفن رابط باید عدد باشد.')
                    ->validateUsername('province', 'استان محل سکونت باید فقط حروف باشد.')
                    ->validateUsername('city', 'شهر محل سکونت باید فقط حروف باشد.');
                //-----
                if (!in_array($this->data['identity']->role_id, [AUTH_ROLE_COLLEGE_STUDENT, AUTH_ROLE_GRADUATE])) {
                    $form->validateUsername('school', 'مدرسه محل تحصیل باید فقط حروف باشد.')
                        ->isIn('degree', array_merge([0], array_keys(EDU_FIELDS)), 'رشته تحصیلی انتخاب شده نامعتبر است.');
                }
                //-----
                if ($this->data['identity']->role_id != AUTH_ROLE_STUDENT) {
                    $form->isIn('soldiery', [0, 1, 2, 3, 4], 'وضعیت سربازی انتخاب شده نامعتبر است.')
                        ->isLengthEquals('soldiery-place', 4, 'سال پایان خدمت باید شامل ۴ عدد باشد.')
                        ->validate('numeric', 'soldiery-place', 'سال پایان خدمت باید شامل ۴ عدد باشد.')
                        ->isIn('marriage', [MARRIAGE_MARRIED, MARRIAGE_SINGLE, MARRIAGE_DEAD], 'وضعیت تأهل انتخاب شده نامعتبر است.')
                        ->validate('numeric', 'children', 'تعداد فرزند باید عدد باشد.');
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

                $res = $model->update_it('users', $updateFields, 'id=:id', ['id' => $this->data['identity']->id]);
                if (!$res) {
                    $form->setError('خطا در انجام عملیات!');
                } else {
                    $this->auth->storeIdentity(array_merge($this->data['identity'], $updateFields));
                    $infoFlag = 0;
                    if(!empty($this->data['identity']->full_name) && !empty($this->data['identity']->connector_phone) &&
                        !empty($this->data['identity']->n_code) && !empty($this->data['identity']->gender) &&
                        !empty($this->data['identity']->grade)) {
                        if (!in_array($this->data['identity']->role_id, [AUTH_ROLE_COLLEGE_STUDENT, AUTH_ROLE_GRADUATE])) {
                            if(!empty($this->data['identity']->school)) {
                                $infoFlag = 1;
                            }
                        } else {
                            $infoFlag = 1;
                        }
                    }

                    $res2 = $model->update_it('users', [
                        'info_flag' => $infoFlag
                    ], 'id=:id', ['id' => $this->data['identity']->id]);
                    if(!$res2) {
                        $form->setError('خطا در انجام عملیات!');
                    }
                }
            });
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $res = $form->checkForm()->isSuccess();
        if ($form->isSubmit()) {
            if ($res) {
                $this->data['informationSuccess'] = 'رمز عبور با موفقیت تغییر یافت شد.';
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
                    }
                }
                if(!count($form->getError())) {
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
                $res = $model->update_it('users', [
                    'password' => password_hash($values['new-password'], PASSWORD_DEFAULT),
                ], 'id=:id', ['id' => $this->data['identity']->id]);

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
                $this->redirect(base_url('logout?back_url=' . base_url('index#login_modal')), 'رمز عبور با موفقیت تغییر یافت، با رمز عبور جدید وارد شوید.', 1000);
            } else {
                $this->data['passwordErrors'] = $form->getError();
            }
        }
    }

    protected function _checker($returnBoolean = false)
    {
        if (!$this->auth->isLoggedIn()) {
            if ((bool)$returnBoolean) return false;
            $this->error->show_404();
        }

        return true;
    }
}