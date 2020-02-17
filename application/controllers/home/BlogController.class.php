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
            ['bId' => $this->data['blog']['id'], 'pub' => 2], ['c.id DESC'], 5);
        $this->data['commentsCount'] = $model->it_count('comments',
            'blog_id=:bId AND publish=:pub', ['bId' => $this->data['blog']['id'], 'pub' => 2]);
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

        // Extra js
        $this->data['js'][] = $this->asset->script('fe/js/blogJs.js');

        $this->_render_page([
            'pages/fe/blog-details-standard',
        ]);
    }

    public function ajaxLoadMoreCommentsAction()
    {
        if (!is_ajax()) {
            message('error', 200, 'دسترسی غیر مجاز');
        }

        if (empty($_POST['blogId']) || empty($_POST['page']) || !is_numeric($_POST['blogId'])) {
            message('error', 200, 'پارامترهای وارد شده نامعتبر است.');
        }
        $page = is_numeric($_POST['page']) ? $_POST['page'] : 2;
        $page = $page < 2 ? 2 : $page;

        //-----
        $blog = new BlogModel();
        $offset = ($page - 1) * 5;
        $data['comments'] = $blog->getBlogComments('c.blog_id=:bId AND c.publish=:pub',
            ['bId' => $_POST['blogId'], 'pub' => 2], ['c.id DESC'], 5, $offset);

        $model = new Model();
        $commentsCount = $model->it_count('comments',
            'blog_id=:bId AND publish=:pub', ['bId' => $_POST['blogId'], 'pub' => 2]);

        message('success', 200,
            [$this->load->view('templates/fe/comments/blog-comment', $data, true), $commentsCount > ($page * 5)]);
    }

    //-----

    public function searchAction($param)
    {
        $query = isset($param[1]) ? urldecode($param[1]) : (isset($param[0]) ? urldecode($param[0]) : '');
        if (empty($query)) {
            if (isset($_GET['blog-query'])) {
                $param = [$_GET['blog-query']];
                $query = urldecode($param[0]);
            } else {
                $param = ['all'];
                $query = 'نمایش خمه';
            }
        }
        //-----
        $this->data['param'] = $param;
        $this->data['searchText'] = $query;
        $this->data['searchTitle'] = '';
        //-----
        $where = '';
        $bindValues = [];

        $model = new Model();
        $blog = new BlogModel();
        if (isset($param[1])) {
            $this->data['searchTitle'] .= $query;
            switch (strtolower($param[0])) {
                case 'category':
                    $this->data['searchTitle'] = 'دسته‌بندی - ';
                    //-----
                    $where .= '(c.id=:cat';
                    $bindValues['cat'] = $query;
                    //+++++
                    $where .= ') AND ';
                    break;
                case 'writer':
                    $this->data['searchTitle'] = 'نویسنده - ';
                    //-----
                    $where .= '(b.writer LIKE :writer';
                    $bindValues['writer'] = '%' . $query . '%';
                    //+++++
                    $where .= ') AND ';
                    break;
                case 'tag':
                    $this->data['searchTitle'] = 'کلمات کلیدی - ';
                    //-----
                    $where .= '(b.keywords LIKE :kw';
                    $bindValues['kw'] = '%' . $query . '%';
                    //+++++
                    $where .= ') AND ';
                    break;
            }
            //-----
            $sub = $blog->getAllBlog(null, 0, $where . ' b.publish=:pub', [], true);
            $this->data['pagination']['total'] = $model->it_count($sub, null,
                array_merge($bindValues, ['pub' => 1]), false, true);
            $this->data['pagination']['page'] = isset($param[3]) && strtolower($param[2]) == 'page' ? (int)$param[3] : 1;
            $this->data['pagination']['limit'] = 12;
            $this->data['pagination']['offset'] = ($this->data['pagination']['page'] - 1) * $this->data['pagination']['limit'];
            $this->data['pagination']['firstPage'] = 1;
            $this->data['pagination']['lastPage'] = ceil($this->data['pagination']['total'] / $this->data['pagination']['limit']);
            //-----
            $this->data['result'] = $blog->getAllBlog($this->data['pagination']['limit'], $this->data['pagination']['offset'],
                $where . ' b.publish=:pub', array_merge($bindValues, ['pub' => 1]));
            //-----
            if (strtolower($param[0]) == 'category') {
                $category = $model->select_it(null, 'categories', 'category_name', 'id=:id', ['id' => $query]);
                $this->data['searchTitle'] .= count($category) ? $category[0]['category_name'] : 'ناشناخته';
            }
        } else {
            $this->data['searchTitle'] = 'همه - ';
            $this->data['searchTitle'] .= $query;
            if (!empty($query)) {
                $where .= '(b.title LIKE :title OR ';
                $bindValues['title'] = '%' . $query . '%';
                //+++++
                $where .= 'b.abstract LIKE :abs OR ';
                $bindValues['abs'] = '%' . $query . '%';
                //+++++
                $where .= 'b.writer LIKE :writer OR ';
                $bindValues['writer'] = '%' . $query . '%';
                //+++++
                $where .= 'b.keywords LIKE :kw OR ';
                $bindValues['kw'] = '%' . $query . '%';
                //+++++
                $where .= 'c.category_name LIKE :cat';
                $bindValues['cat'] = '%' . $query . '%';
                //+++++
                $where .= ') AND ';
            }
            //-----
            $sub = $blog->getAllBlog(null, 0, $where . ' b.publish=:pub', [], true);
            $this->data['pagination']['total'] = $model->it_count($sub, null,
                array_merge($bindValues, ['pub' => 1]), false, true);
            $this->data['pagination']['page'] = isset($param[2]) && strtolower($param[1]) == 'page' ? (int)$param[2] : 1;
            $this->data['pagination']['limit'] = 12;
            $this->data['pagination']['offset'] = ($this->data['pagination']['page'] - 1) * $this->data['pagination']['limit'];
            $this->data['pagination']['firstPage'] = 1;
            $this->data['pagination']['lastPage'] = ceil($this->data['pagination']['total'] / $this->data['pagination']['limit']);
            //-----
            $this->data['result'] = $blog->getAllBlog($this->data['pagination']['limit'], $this->data['pagination']['offset'],
                $where . ' b.publish=:pub', array_merge($bindValues, ['pub' => 1]));
        }
        //-----

        // Register & Login actions
        $this->_register(['captcha' => ACTION]);
        $this->_login(['captcha' => ACTION]);

        $this->data['title'] = titleMaker(' | ', set_value($this->setting['main']['title'] ?? ''), 'جستحو', $query);

        $this->_render_page([
            'pages/fe/blog-search',
        ]);
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
            'blog-name', 'blog-mobile', 'blog-body', 'blogCaptcha'
        ])->setMethod('post');
        try {
            $form->beforeCheckCallback(function ($values) use ($model, $form, $param) {
                $form->isRequired(['blog-name', 'blog-body', 'blogCaptcha'], 'فیلدهای اجباری را خالی نگذارید.');

                if (!count($form->getError())) {
                    if ($model->is_exist('comments', 'ip_address=:ip AND created_on>:t',
                        ['ip' => get_client_ip_env(), 't' => (strtotime(date('Y/m/d', time())) - 86400)])) { // 86400 ==> 1 day
                        $form->setError('دیدگاه شما ثبت شده است.');
                    } else {
                        $form->validatePersianName('blog-name', 'نام باید حروف فارسی باشد.');
                        //-----
                        if (!empty($values['mobile'])) {
                            $form->validatePersianMobile('blog-mobile');
                        }
                        //-----
                        $config = getConfig('config');
                        if (!isset($config['captcha_session_name']) ||
                            !isset($_SESSION[$config['captcha_session_name']][$param['captcha']]) ||
                            !isset($param['captcha']) ||
                            encryption_decryption(ED_DECRYPT, $_SESSION[$config['captcha_session_name']][$param['captcha']]) != strtolower($values['blogCaptcha'])) {
                            $form->setError('کد وارد شده با کد تصویر مغایرت دارد. دوباره تلاش کنید.');
                        }
                    }
                }
            })->afterCheckCallback(function ($values) use ($model, $form) {
                $res = $model->insert_it('comments', [
                    'blog_id' => $this->data['blog']['id'],
                    'name' => trim($values['blog-name']),
                    'mobile' => convertNumbersToPersian(trim($values['blog-mobile']), true),
                    'body' => trim($values['blog-body']),
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
                $this->data['blogCommentValues'] = $form->getValues();
            }
        }
    }
}