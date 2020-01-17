<?php

include_once 'AbstractController.class.php';

class BlogController extends AbstractController
{
    public function indexAction()
    {
        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'بلاگ');

        $this->_render_page([
            'pages/fe/blog',
        ]);
    }

    public function detailAction($param)
    {
        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'بلاگ');

        $this->_render_page([
            'pages/fe/blog',
        ]);
    }
}