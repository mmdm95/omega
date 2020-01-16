<?php

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
            'title', 'slug', 'contact', 'capacity', 'base_price', 'image', 'start_at', 'end_at', 'active_at', 'deactive_at', 'place', 'status',
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

        // Submit checker
        $this->_eventSubmit();

        $this->data['param'] = $param;
        $this->data['event'] = $model->select_it(null, 'plans', '*', 'slug=:slug', ['slug' => $param[0]])[0];
        $this->data['event']['options'] = json_decode($this->data['event']['options'], true);
        //-----
        $sub = $model->select_it(null, 'factors', ['COUNT(*)'], 'plan_id=:pId', [],
            ['plan_id'], null, null, null, true);
        $this->data['event']['filled'] = $model->it_count($sub, null, ['pId' => $this->data['event']['id']], false, true);
        //-----
        $this->data['event']['gallery'] = $model->select_it(null, 'plan_images', ['image'], 'plan_id=:pId', ['pId' => $this->data['event']['id']]);
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
        $this->data['relatedEvents'] = $model->select_it(null, 'plans', ['title', 'slug', 'image', 'base_price', 'contact'],
            $generalWhere . ' AND (' . $audienceTitle . $relatedTitle . ')',
            array_merge($generalWhereParam, $audienceTitleParam, $relatedTitleParam),
            null, ['id DESC'], 5);

        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جزئیات طرح', $this->data['event']['title']);

        // Extra js
        $this->data['js'][] = $this->asset->script('fe/js/eventJs.js');

        $this->_render_page([
            'pages/fe/event-detail',
        ]);
    }

    protected function _eventSubmit()
    {
        if($this->auth->isLoggedIn()) {

        }
    }
}