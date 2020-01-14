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
        if(!$model->is_exist('plans', 'slug=:slug', ['slug' => $param[1]]) || !count($this->data['event'])) {
            $_SESSION['user-event'] = 'جزئیاتی برای طرح درخواست شده وجود ندارد';
            $this->redirect('user/dashboard');
        }

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جزئیات طرح', $this->data['event']['title']);

//        $this->_render_page([
//            'pages/fe/',
//        ]);
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
        $form->setFieldsName([''])->setMethod('post');
        try {
            $form->beforeCheckCallback(function () use ($model, $form) {
                $form->isRequired([''], 'فیلدهای اجباری را خالی نگذارید.');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('', [
                    '' => '',
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
        $form->setFieldsName([''])->setMethod('post');
        try {
            $form->beforeCheckCallback(function () use ($model, $form) {
                $form->isRequired([''], 'فیلدهای اجباری را خالی نگذارید.');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('', [
                    '' => '',
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
                $this->data['passwordSuccess'] = 'رمز عبور با موفقیت تغییر یافت شد.';
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