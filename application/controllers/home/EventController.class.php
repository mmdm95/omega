<?php

use HForm\Form;

include_once 'AbstractController.class.php';

class EventController extends AbstractController
{
    public function indexAction($param)
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
            $this->redirect(base_url('event'));
        }

        $this->data['param'] = $param;
        $this->data['event'] = $model->select_it(null, 'plans', '*', 'slug=:slug', ['slug' => $param[0]])[0];
        $this->data['event']['options'] = json_decode($this->data['event']['options'], true);
        //-----
        $sub = $model->select_it(null, 'factors', ['COUNT(*)'], 'plan_id=:pId', [],
            ['plan_id'], null, null, null, true);
        $this->data['event']['filled'] = $model->it_count($sub, null, ['pId' => $this->data['event']['id']], false, true);
        //-----
        $this->data['event']['gallery'] = $model->select_it(null, 'plan_images', ['image'], 'plan_id=:pId', ['pId' => $this->data['event']['id']]);

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
                if (!$form->isChecked('rule_agree')) {
                    $form->setError('لطفا ابتدا قوانین و مقررات را مطالعه کنید و در صورت موافق بودن با آنها، علامت موافق هستم را فعال کنید.');
                } else {
                    $values['total_amount'] = $this->data['event']['base_price'];
                    $values['options'] = [];
                    foreach ($this->data['event']['options'] as $k => $option) {
                        $chkName = 'select-chk-' . ($k + 1);
                        $postChk = $_POST[$chkName] ?? null;

                        var_dump($postChk);

                        if (isset($postChk)) {
                            if (($option['radio'] == 1 && is_array($postChk)) || ($option['radio'] == 2 && is_string($postChk))) {
                                if (is_array($postChk)) {
                                    $validKeys = array_keys($option['name']);
                                    foreach ($postChk as $k2) {
                                        if (in_array($k2, $validKeys)) {
                                            $values['total_amount'] += is_numeric($option['price'][$k2]) ? (int)$option['price'][$k2] : 0;
                                            $values['options'][$k]['title'] = $option['title'];
                                            $values['options'][$k]['radio'] = $option['radio'];
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
                        } else {
                            $form->setError('آیتم‌های خرید دستکاری شده‌اند! لطفا دوباره تلاش کنید.');
                            break;
                        }
                    }

                    if (count($form->getError()) && empty($values['options'])) {
                        $form->removeErrors()->setError('هیچ آیتمی انتخاب نشده است.');
                    }

                    var_dump($values);
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = false;
//                $res = $model->update_it('users', [
//                    'password' => password_hash($values['new-password'], PASSWORD_DEFAULT),
//                ], 'id=:id', ['id' => $this->data['identity']->id]);

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
                $this->redirect(base_url('user/event/detail/' . $this->data['event']['slug']));
            } else {
                $this->data['eventSubmitErrors'] = $form->getError();
            }
        }
    }
}