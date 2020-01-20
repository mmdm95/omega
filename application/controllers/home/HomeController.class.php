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
            'title', 'slug', 'contact', 'capacity', 'total_price', 'image', 'start_at', 'end_at', 'active_at', 'deactive_at', 'place', 'status',
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

    public function faqAction()
    {
        $model = new Model();
        $this->data['faq'] = $model->select_it(null, 'faq');
        //-----
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'سؤالات متداول');

        $this->_render_page([
            'pages/fe/faqs',
        ]);
    }

    public function contactUsAction()
    {
        $this->_contactSubmit();
        //-----
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'سؤالات متداول');

        $this->_render_page([
            'pages/fe/contact',
        ]);
    }

    protected function _contactSubmit()
    {
        //-----
        $model = new Model();
        $this->data['contactErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_contact'] = $form->csrfToken('contactForm');
        $form->setFieldsName([
            'subject', 'name', 'mobile', 'message',
        ])->setMethod('post');
        try {
            $form->beforeCheckCallback(function () use ($model, $form) {
                $form->isRequired(['subject', 'name', 'phone', 'message'], 'تمام فیلدها اجباری هستند.');
                $form->validatePersianName('name', 'نام باید حروف فارسی باشد.')
                    ->validate('numeric', 'phone', 'شماره باید از نوع عدد باشد.');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('contact_us', [
                    'full_name' => trim($values['name']),
                    'phone' => trim($values['phone']),
                    'subject' => trim($values['subject']),
                    'body' => trim($values['message']),
                    'status' => 0,
                    'sent_at' => time(),
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
                $this->data['contactSuccess'] = 'پیام با موفقیت ارسال شد.';
            } else {
                $this->data['contactErrors'] = $form->getError();
            }
        }
    }

    protected function _newsletter()
    {
        $model = new Model();
        $this->data['newsletterErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_newsletter']  = $form->csrfToken('addNewletter');
        $form->setFieldsName(['newsletter-mobile'])->setMethod('post');
        try {
            $form->beforeCheckCallback(function () use ($model, $form) {
                $form->isRequired(['newsletter-mobile'], 'فیلد موبایل اجباری می‌باشد.')
                    ->validatePersianMobile('newsletter-mobile');
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = true;
                if (!$model->is_exist('newsletters', 'mobile=:mobile', ['mobile' => $values['newsletter-mobile']])) {
                    $res = $model->insert_it('newsletters', [
                        'mobile' => trim($values['newsletter-mobile']),
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