<?php

include_once 'AbstractController.class.php';

class UserController extends AbstractController
{
    public function dashboardAction()
    {
        $this->_checker();

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'داشبورد');

        $this->_render_page([
            'pages/fe/user-profile',
        ]);
    }

    public function userInformationAction()
    {
        $this->_checker();

        $model = new Model();

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'فرم اطلاعات');

        $this->_render_page([
            'pages/fe/user-information',
        ]);
    }

    protected function _saveInformation()
    {

    }

    protected function _passwordChange()
    {

    }

    protected function _checker()
    {
        if(!$this->auth->isLoggedIn()) {
            $this->error->show_404();
        }
    }
}