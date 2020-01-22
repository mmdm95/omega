<?php

use HForm\Form;

include_once 'AbstractController.class.php';

class EventController extends AbstractController
{
    public function eventsAction($param)
    {
        $model = new Model();
        //-----
        $this->data['pagination']['total'] = $model->it_count('plans', 'publish=:pub', ['pub' => 1]);
        $this->data['pagination']['page'] = isset($param[1]) && strtolower($param[0]) == 'page' ? (int)$param[1] : 1;
        $this->data['pagination']['limit'] = 12;
        $this->data['pagination']['offset'] = ($this->data['pagination']['page'] - 1) * $this->data['pagination']['limit'];
        $this->data['pagination']['firstPage'] = 1;
        $this->data['pagination']['lastPage'] = ceil($this->data['pagination']['total'] / $this->data['pagination']['limit']);
        //-----
        $this->data['events'] = $model->select_it(null, 'plans', [
            'title', 'slug', 'contact', 'capacity', 'total_price', 'image', 'start_at', 'end_at', 'active_at', 'deactive_at', 'place', 'status',
        ], 'publish=:pub', ['pub' => 1], null, ['id DESC'], $this->data['pagination']['limit'], $this->data['pagination']['offset']);

        // Count active events
        $this->data['activeEventsCount'] = $model->it_count('plans', 'publish=:pub AND status=:status', ['pub' => 1, 'status' => PLAN_STATUS_ACTIVATE]);

        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'طرح‌ها');

        $this->_render_page([
            'pages/fe/all-events',
        ]);
    }

    public function detailAction($param)
    {
        $model = new Model();

        if (!isset($param[0]) || !$model->is_exist('plans', 'slug=:slug', ['slug' => $param[0]])) {
            $this->redirect(base_url('event/events'));
        }

        $this->data['param'] = $param;
        $this->data['event'] = $model->select_it(null, 'plans', '*', 'slug=:slug', ['slug' => $param[0]])[0];
        $this->data['event']['options'] = json_decode($this->data['event']['options'], true);
        //-----
        $sub = $model->select_it(null, 'factors', ['COUNT(*)'], 'plan_id=:pId AND payed_amount IS NOT NULL AND payed_amount>:pa', [],
            ['plan_id'], null, null, null, true);
        $this->data['event']['filled'] = $model->it_count($sub, null, ['pId' => $this->data['event']['id'], 'pa' => 0], false, true);
        //-----
        $this->data['event']['brochure'] = $model->select_it(null, 'plan_brochure', ['image'], 'plan_id=:pId', ['pId' => $this->data['event']['id']]);
        //-----
        $this->data['event']['gallery'] = $model->select_it(null, 'plan_images', ['image'], 'plan_id=:pId', ['pId' => $this->data['event']['id']]);
        //-----
        $this->data['event']['videos'] = $model->select_it(null, 'plan_videos', ['video'], 'plan_id=:pId', ['pId' => $this->data['event']['id']]);

        // Event submission
        $this->_eventSubmit();
        //-----
        $generalWhere = 'id!=:id';
        $generalWhereParam = ['id' => $this->data['event']['id']];
        //=====
        $pieces = explode(',', $this->data['event']['contact']);
        $tmpLastIdx = count($pieces) - 1;
        $audienceTitle = '';
        $audienceTitleParam = [];
        foreach ($pieces as $k => $au) {
            $audienceTitle .= 'contact REGEXP :c' . $k;
            $audienceTitleParam['c' . $k] = '^' . trim($au) . '$';
            if ($k != $tmpLastIdx) {
                $audienceTitle .= ' OR ';
            }
        }
        //=====
        $pieces = explode(' ', $this->data['event']['title']);
        $tmpLastIdx = count($pieces) - 1;
        $relatedTitle = '';
        $relatedTitleParam = [];
        foreach ($pieces as $k => $title) {
            $relatedTitle .= 'title LIKE :t' . $k;
            $relatedTitleParam['t' . $k] = '%' . trim($title) . '%';
            if ($k != $tmpLastIdx) {
                $relatedTitle .= ' OR ';
            }
        }
        if (empty($audienceTitle) && empty($relatedTitle)) {
            $audienceTitle = '1';
        } elseif (!empty($audienceTitle) && !empty($relatedTitle)) {
            $relatedTitle = ' OR ' . $relatedTitle;
        }
        //=====
        $this->data['relatedEvents'] = $model->select_it(null, 'plans', ['title', 'slug', 'image', 'total_price', 'contact'],
            $generalWhere . ' AND (' . $audienceTitle . $relatedTitle . ')',
            array_merge($generalWhereParam, $audienceTitleParam, $relatedTitleParam),
            null, ['id DESC'], 5);

        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

//        var_dump($this->data['event']['options']);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جزئیات طرح', $this->data['event']['title']);

        // Extra js
        $this->data['js'][] = $this->asset->script('fe/js/eventJs.js');

        $this->_render_page([
            'pages/fe/event-detail',
        ]);
    }

    protected function _eventSubmit()
    {
        if (!$this->auth->isLoggedIn()) return;

        $model = new Model();
        $this->data['eventSubmitErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_save_event'] = $form->csrfToken('saveUserEvent');

        $form->setFieldsName(['save-event-btn', 'rule_agree'])
            ->setDefaults('rule_agree', 'off')
            ->setMethod('post', [], ['rule_agree']);
        try {
            $form->beforeCheckCallback(function (&$values) use ($model, $form) {
                if ($model->is_exist('factors', 'user_id=:uId AND plan_id=:pId', ['uId' => $this->data['identity']->id, 'pId' => $this->data['event']['id']])) {
                    $msg = 'کاربر گرامی، این طرح قبلا برای شما ثبت شده است. شما می‌توانید از طریق ';
                    $msg .= "<a href='" . base_url('user/dashboard') . "'>" . 'حساب کاربری' . '</a>';
                    $msg .= ' نسبت به پرداخت وجه طرح اقدام نمایید.';
                    $form->setError($msg);
                } else {
                    if (!$form->isChecked('rule_agree')) {
                        $form->setError('لطفا ابتدا قوانین و مقررات را مطالعه کنید و در صورت موافق بودن با آنها، گزینه موافق هستم را علامت بزنید.');
                    } else {
                        $values['total_amount'] = $this->data['event']['base_price'];
                        $values['options'] = [];
                        foreach ($this->data['event']['options'] as $k => $option) {
                            if ($option['forced'] == 2) {
                                foreach ($option['name'] as $k2 => $name) {
                                    $values['total_amount'] += is_numeric($option['price'][$k2]) ? (int)$option['price'][$k2] : 0;
                                    $values['options'][$k]['title'] = $option['title'];
                                    $values['options'][$k]['radio'] = $option['radio'];
                                    $values['options'][$k]['forced'] = $option['forced'];
                                    $values['options'][$k]['name'][$k2] = $option['name'][$k2];
                                    $values['options'][$k]['desc'][$k2] = $option['desc'][$k2];
                                    $values['options'][$k]['price'][$k2] = $option['price'][$k2];
                                }
                            } else {
                                $chkName = 'select-chk-' . ($k + 1);
                                $postChk = $_POST[$chkName] ?? null;

                                if (isset($postChk)) {
                                    if (($option['radio'] == 1 && is_array($postChk)) || ($option['radio'] == 2 && is_string($postChk))) {
                                        if (is_array($postChk)) {
                                            $validKeys = array_keys($option['name']);
                                            foreach ($postChk as $k2) {
                                                if (in_array($k2, $validKeys)) {
                                                    $values['total_amount'] += is_numeric($option['price'][$k2]) ? (int)$option['price'][$k2] : 0;
                                                    $values['options'][$k]['title'] = $option['title'];
                                                    $values['options'][$k]['radio'] = $option['radio'];
                                                    $values['options'][$k]['forced'] = $option['forced'];
                                                    $values['options'][$k]['name'][$k2] = $option['name'][$k2];
                                                    $values['options'][$k]['desc'][$k2] = $option['desc'][$k2];
                                                    $values['options'][$k]['price'][$k2] = $option['price'][$k2];
                                                } else {
                                                    $form->setError('آیتم‌های خرید دستکاری شده‌اند! لطفا دوباره تلاش کنید.');
                                                    break;
                                                }
                                            }
                                        } else {
                                            if (isset($option['price'][$postChk])) {
                                                $values['total_amount'] += is_numeric($option['price'][$postChk]) ? (int)$option['price'][$postChk] : 0;
                                                $values['options'][$k]['title'] = $option['title'];
                                                $values['options'][$k]['radio'] = $option['radio'];
                                                $values['options'][$k]['name'][$postChk] = $option['name'][$postChk];
                                                $values['options'][$k]['desc'][$postChk] = $option['desc'][$postChk];
                                                $values['options'][$k]['price'][$postChk] = $option['price'][$postChk];
                                            } else {
                                                $form->setError('آیتم‌های خرید دستکاری شده‌اند! لطفا دوباره تلاش کنید.');
                                                break;
                                            }
                                        }
                                    } else {
                                        $form->setError('آیتم‌های خرید دستکاری شده‌اند! لطفا دوباره تلاش کنید.');
                                        break;
                                    }
                                }
                            }
                        }

                        if (count($form->getError()) && empty($values['options'])) {
                            $form->removeErrors()->setError('هیچ آیتمی انتخاب نشده است.');
                        }
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $model->transactionBegin();
                // Create factor code
                $common = new CommonModel();
                $factorCode = $common->generate_random_unique_code('factors', 'factor_code', 'OMG_', 6, 15, 8, CommonModel::DIGITS);
                $factorCode = 'OMG_' . $factorCode;

                $res = $model->insert_it('factors', [
                    'user_id' => $this->data['identity']->id,
                    'plan_id' => $this->data['event']['id'],
                    'factor_code' => $factorCode,
                    'username' => $this->data['identity']->username,
                    'full_name' => $this->data['identity']->full_name,
                    'options' => json_encode($values['options']),
                    'total_amount' => convertNumbersToPersian($values['total_amount'], true),
                    'created_at' => time()
                ], [
                    'payed_amount' => null
                ]);

                if ($res) {
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
                if ($this->data['identity']->info_flag == 1) {
                    $this->redirect(base_url('user/event/detail/' . $this->data['event']['slug']));
                }
                $_SESSION['event-eventSubmit'] = 'برای پرداخت ابتدا فیلدهای اجباری را تکمیل کنید.';
                $this->redirect(base_url('user/informationCompletion?back_url=' . base_url('user/event/detail/' . $this->data['event']['slug'])));
            } else {
                $this->data['eventSubmitErrors'] = $form->getError();
            }
        }
    }
}