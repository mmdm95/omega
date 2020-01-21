<?php

include_once 'AbstractController.class.php';

class BlogController extends AbstractController
{
    public function allBlogAction($param)
    {
        $model = new Model();
        //-----
        $this->data['pagination']['total'] = $model->it_count('blog', 'publish=:pub', ['pub' => 1]);
        $this->data['pagination']['page'] = isset($param[1]) && strtolower($param[0]) == 'page' ? (int)$param[1] : 1;
        $this->data['pagination']['limit'] = 12;
        $this->data['pagination']['offset'] = ($this->data['pagination']['page'] - 1) * $this->data['pagination']['limit'];
        $this->data['pagination']['firstPage'] = 1;
        $this->data['pagination']['lastPage'] = ceil($this->data['pagination']['total'] / $this->data['pagination']['limit']);
        //-----
        $this->data['blog'] = $model->select_it(null, 'blog', [
            'image', 'title', 'slug', 'abstract', 'writer', 'created_at', 'updated_at'
        ], 'publish=:pub', ['pub' => 1], null, ['id DESC'], $this->data['pagination']['limit'], $this->data['pagination']['offset']);
        //-----
        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name'],
            'publish=:pub', ['pub' => 1]);
        //-----
        $this->data['related'] = $model->select_it(null, 'blog', [
            'image', 'title', 'slug', 'writer', 'created_at', 'updated_at'
        ], 'publish=:pub', ['pub' => 1], null, ['id DESC'], 5);

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
        $model = new Model();
        //-----
        if(!isset($param[0]) || !$model->is_exist('blog', 'slug=:slug AND publish=:pub', ['slug' => $param[0], 'pub' => 1])) {
            $_SESSION['blog-detail-err'] = 'پارامترهای ارسالی برای مشاهده بلاگ نادرست هستند!';
            $this->redirect(base_url('blog/allBlog'));
        }
        //-----
        $blog = new BlogModel();
        $this->data['blog'] = $blog->getBlogDetail(['slug' => $param[0]]);

        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'بلاگ');

        $this->_render_page([
            'pages/fe/blog',
        ]);
    }

    public function searchAction($param)
    {
        $query = '';
        if(isset($param[0]) && isset($param[1])) {
            $query = $param[1];
        }

        $model = new Model();
        switch (strtolower($param[0])) {
            case 'category':
                break;
            case 'writer':
                break;
            case 'tag':
                break;
        }
    }
}