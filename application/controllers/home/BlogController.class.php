<?php

use HForm\Form;

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
        if (!isset($param[0]) || !$model->is_exist('blog', 'slug=:slug AND publish=:pub', ['slug' => $param[0], 'pub' => 1])) {
            $_SESSION['blog-detail-err'] = 'پارامترهای ارسالی برای مشاهده بلاگ نادرست هستند!';
            $this->redirect(base_url('blog/allBlog'));
        }
        //-----
        $blog = new BlogModel();
        $this->data['blog'] = $blog->getBlogDetail(['slug' => $param[0]]);
        $next = $blog->getSiblingBlog('b.id>:id', ['id' => $this->data['blog']['id']], ['id DESC']);
        $this->data['nextBlog'] = count($next) ? $next : $blog->getSiblingBlog('b.id<:id', ['id' => $this->data['blog']['id']], ['id ASC']);
        $prev = $blog->getSiblingBlog('b.id<:id', ['id' => $this->data['blog']['id']], ['id DESC']);
        $this->data['prevBlog'] = count($prev) ? $prev : $blog->getSiblingBlog('b.id>:id', ['id' => $this->data['blog']['id']], ['id ASC']);
        //-----
        $this->data['lastPosts'] = $model->select_it(null, 'blog', [
            'image', 'title', 'slug', 'writer', 'created_at', 'updated_at'
        ], 'publish=:pub', ['pub' => 1], null, ['id DESC'], 5);
        //-----
        $this->data['comments'] = $blog->getBlogComments('c.blog_id=:bId AND c.publish=:pub',
            ['bId' => $this->data['blog']['id'], 'pub' => 1], ['id DESC'], 5);
        $this->data['commentsCount'] = $model->it_count('comments',
            'blog_id=:bId AND publish=:pub', ['bId' => $this->data['blog']['id'], 'pub' => 1]);
        //-----
        $this->data['categories'] = $model->select_it(null, 'categories', ['id', 'category_name'],
            'publish=:pub', ['pub' => 1]);
        //-----
        $this->data['related'] = $blog->getRelatedBlog($this->data['blog'], 3);

        // Comment form submit
        $this->_commentSubmit(['captcha' => ACTION]);

        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'بلاگ');

        $this->_render_page([
            'pages/fe/blog-details-standard',
        ]);
    }

    public function searchAction($param)
    {
        $query = '';
        if (isset($param[0]) && isset($param[1])) {
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

    //-----

    protected function _commentSubmit($param)
    {
        $model = new Model();
        $this->data['blogCommentErrors'] = [];
        $this->load->library('HForm/Form');
        $form = new Form();
        $this->data['form_token_blog_comment'] = $form->csrfToken('blogComment');
        $form->setFieldsName([
            'name', 'mobile', 'body', 'captcha'
        ])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form, $param) {
                $form->isRequired(['name', 'body', 'captcha'], 'فیلدهای اجباری را خالی نگذارید.');

                if (!count($form->getError())) {
                    if ($model->is_exist('comments', 'ip_address=:ip AND created_on<:t',
                        ['ip' => get_client_ip_env(), 't' => (strtotime('Y-m-d') - 86400)])) { // 86400 ==> 1 day
                        $form->setError('دیدگاه شما ثبت شده است.');
                    } else {
                        $form->validatePersianName('name', 'نام باید حروف فارسی باشد.');
                        //-----
                        if(!empty($values['mobile'])) {
                            $form->validatePersianMobile('mobile');
                        }
                        //-----
                        $config = getConfig('config');
                        if (!isset($config['captcha_session_name']) ||
                            !isset($_SESSION[$config['captcha_session_name']][$param['captcha']]) ||
                            !isset($param['captcha']) ||
                            encryption_decryption(ED_DECRYPT, $_SESSION[$config['captcha_session_name']][$param['captcha']]) != $values['registerCaptcha']) {
                            $form->setError('کد وارد شده با کد تصویر مغایرت دارد. دوباره تلاش کنید.');
                        }
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('comments', [
                    'blog_id' => $this->data['blog']['id'],
                    'name' => trim($values['name']),
                    'mobile' => convertNumbersToPersian(trim($values['mobile']), true),
                    'body' => trim($values['body']),
                    'publish' => 0,
                    'ip_address' => get_client_ip_env(),
                    'created_on' => time(),
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
                $this->data['blogCommentSuccess'] = 'دیدگاه شما ثبت شد.پس از تأیید، دیدگاه در سایت نمایش داده می‌شود.';
            } else {
                $this->data['blogCommentErrors'] = $form->getError();
            }
        }
    }
}