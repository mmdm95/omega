<?php

include_once 'AbstractController.class.php';

class EventController extends AbstractController
{
    public function indexAction()
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'صفحه اصلی');

        $this->_render_page([
            'pages/fe/all-events',
        ]);
    }

    public function detailAction($param)
    {
        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جزئیات طرح');

        $this->_render_page([
            'pages/fe/event-detail',
        ]);
    }
}