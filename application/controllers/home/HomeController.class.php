<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

use HForm\Form;


include_once 'AbstractController.class.php';

class HomeController extends AbstractController
{
    public function indexAction()
    {
        $model = new Model();

        $this->data['topEvents'] = $model->select_it(null, 'plans', [
            'title', 'slug', 'contact', 'capacity', 'base_price', 'image', 'start_at', 'end_at', 'active_at', 'deactive_at', 'place', 'status',
        ], 'publish=:pub', ['pub' => 1], null, ['id DESC'], 3);
        //-----
        $this->data['feedback'] = $model->select_it(null, 'site_feedback', '*', 'show_in_page=:sip', ['sip' => 1]);
        //-----
        $this->data['helpfulLinks'] = $model->select_it(null, 'helpful_links');
        //-----

        // Newsletter submission
        $this->_newsletter();

        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'صفحه اصلی');

        $this->_render_page([
            'pages/fe/index',
        ]);
    }

    protected function _newsletter()
    {
        $model = new Model();
        $this->data['newsletterErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_newsletter'] = $form->csrfToken('addNewletter');
        $form->setFieldsName(['mobile'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function () use ($model, $form) {
                $form->isRequired(['mobile'], 'فیلد موبایل اجباری می‌باشد.')
                    ->validatePersianMobile('mobile');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = true;
                if (!$model->is_exist('newsletters', 'mobile=:mobile', ['mobile' => $values['mobile']])) {
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
    }
}