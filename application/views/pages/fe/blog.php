<section class="header-breadcrumb bgimage overlay overlay--dark">    <div class="bg_image_holder">        <img src="<?= base_url($setting['pages']['all']['topImage']['image'], '/'); ?>" alt="">    </div>    <!-- menu part -->    <?php $this->view('templates/fe/home-menu-part', $data); ?>    <!-- End Menu part -->    <div class="breadcrumb-wrapper content_above">        <div class="container">            <div class="row">                <div class="col-lg-12 text-center">                    <h1 class="page-title aviny">وبلاگ</h1>                    <nav aria-label="breadcrumb">                        <ol class="breadcrumb">                            <li class="breadcrumb-item"><a href="<?= base_url('index'); ?>">اُمگا</a></li>                            <li class="breadcrumb-item active iranyekan-light" aria-current="page">اخبار و اطلاعیه‌ها                            </li>                        </ol>                    </nav>                </div>            </div>        </div>    </div><!-- ends: .breadcrumb-wrapper --></section><section class="blog-area blog-grid section-padding-strict border-bottom section-bg">    <div class="container">        <div class="row">            <div class="col-md-8">                <div class="row">                    <?php if ($pagination['total'] != 0): ?>                        <?php foreach ($blog as $blg): ?>                            <div class="col-lg-6 col-md-6">                                <div class="grid-single">                                    <div class="card post--card shadow-sm">                                        <figure>                                            <a href="<?= base_url('blog/detail/' . $blg['slug']) ?>">                                                <img src="<?= base_url($blg['image']); ?>" alt="<?= $blg['title']; ?>">                                            </a>                                        </figure>                                        <div class="card-body blog-card-size">                                            <h6>                                                <a href="<?= base_url('blog/detail/' . $blg['slug']); ?>">                                                    <?= $blg['title']; ?>                                                </a>                                            </h6>                                            <ul class="post-meta d-flex list-unstyled">                                                <li class="font-size-sm">                                                    <?= jDateTime::date('j F Y', $blg['created_at']); ?>                                                </li>                                                <li class="font-size-sm">                                                    توسط:                                                    <a href="<?= base_url('blog/search/writer/' . urlencode($blg['writer'])); ?>">                                                        <?= $blg['writer']; ?>                                                    </a>                                                </li>                                            </ul>                                            <p class="text-justify iranyekan-light">                                                <?= character_limiter($blg['abstract'], 150); ?>                                            </p>                                        </div>                                    </div><!-- End: .card -->                                </div>                            </div><!-- ends: .col-lg-4 -->                        <?php endforeach; ?>                        <?php if ($pagination['total'] != 0 && ($pagination['lastPage'] - $pagination['firstPage']) != 0): ?>                            <div class="row">                                <div class="col-lg-12">                                    <nav class="navigation pagination d-flex justify-content-end" role="navigation">                                        <div class="nav-links">                                            <?php if ($pagination['firstPage'] == $pagination['page']): ?>                                                <span aria-current="page"                                                      class="la la-long-arrow-right page-numbers current"></span>                                            <?php else: ?>                                                <a href="<?= base_url('blog/allBlog/page/' . $pagination['firstPage']); ?>"                                                   class="prev page-numbers">                                                    <span class="la la-long-arrow-right"></span>                                                </a>                                            <?php endif; ?>                                            <?php if (($pagination['page'] - 4) > $pagination['firstPage']): ?>                                                <a class="page-numbers"                                                   href="<?php echo BASE_URL . 'event/page/' . $pagination['firstPage']; ?>">                                                    <?= convertNumbersToPersian($pagination['firstPage']); ?>                                                </a>                                                <span class="mx-3">...</span>                                            <?php endif; ?>                                            <?php for ($i = $pagination['page'] - 4; $i < $pagination['page']; $i++): ?>                                                <?php if ($i <= 0) continue; ?>                                                <?php if ($i == $pagination['page']): ?>                                                    <span aria-current="page"                                                          class="page-numbers current"><?= convertNumbersToPersian($i); ?></span>                                                <?php else: ?>                                                    <a class="page-numbers"                                                       href="<?= base_url('blog/allBlog/page/' . $i); ?>">                                                        <?= convertNumbersToPersian($i); ?>                                                    </a>                                                <?php endif; ?>                                            <?php endfor; ?>                                            <?php for ($i = $pagination['page']; $i <= $pagination['page'] + 4 && $i <= $pagination['lastPage']; $i++): ?>                                                <?php if ($i == $pagination['page']): ?>                                                    <span aria-current="page"                                                          class="page-numbers current"><?= convertNumbersToPersian($i); ?></span>                                                <?php else: ?>                                                    <a class="page-numbers"                                                       href="<?= base_url('blog/allBlog/page/' . $i); ?>">                                                        <?= convertNumbersToPersian($i); ?>                                                    </a>                                                <?php endif; ?>                                            <?php endfor; ?>                                            <?php if (($pagination['page'] + 4) < $pagination['lastPage']): ?>                                                <span class="mx-3">...</span>                                                <a class="page-numbers"                                                   href="<?= base_url('blog/allBlog/page/' . $pagination['lastPage']); ?>">                                                    <?= convertNumbersToPersian($pagination['lastPage']); ?>                                                </a>                                            <?php endif; ?>                                            <?php if ($pagination['lastPage'] == $pagination['page']): ?>                                                <span aria-current="page"                                                      class="la la-long-arrow-left page-numbers current"></span>                                            <?php else: ?>                                                <a href="<?= base_url('blog/allBlog/page/' . $pagination['lastPage']); ?>"                                                   class="next page-numbers">                                                    <span class="la la-long-arrow-left"></span>                                                </a>                                            <?php endif; ?>                                        </div>                                    </nav>                                </div>                            </div>                        <?php endif; ?>                    <?php else: ?>                        <div class="col-sm-12">                            <div class="widget-wrapper">                                <div class="widget-default">                                    <div class="py-2 px-4 iranyekan-regular">                                        <i class="la la-info-circle font-size-semi-huge float-left"></i>                                        <span class="ml-2 mt-2 pb-3 font-size-md d-inline-block">                                            موردی وجود ندارد.                                        </span>                                    </div>                                </div>                            </div>                        </div>                    <?php endif; ?>                </div>            </div><!-- ends: .col-lg-8 -->            <div class="col-md-4 mt-5 mt-md-0">                <div class="sidebar">                    <!-- search widget -->                    <div class="widget-wrapper">                        <div class="search-widget">                            <form action="<?= base_url('blog/search'); ?>" method="get">                                <div class="input-group">                                    <input type="text" name="blog-query" class="" required                                           value="<?= $_SESSION['blog-search-query'] ?? ''; ?>" placeholder="جست و جو">                                    <button type="submit"><i class="la la-search"></i></button>                                </div>                            </form>                        </div>                    </div><!-- ends: .widget-wrapper -->                    <?php if (count($categories)): ?>                        <!-- category widget -->                        <div class="widget-wrapper">                            <div class="widget-default">                                <div class="widget-header">                                    <h6 class="widget-title aviny">دسته‌بندی‌ها</h6>                                </div>                                <div class="widget-content">                                    <div class="category-widget">                                        <ul>                                            <?php foreach ($categories as $category): ?>                                                <li class="arrow-list4">                                                    <a href="<?= base_url('blog/search/category/' . $category['id'] . '/' . urlencode($category['category_name'])); ?>">                                                        <?= $category['category_name']; ?>                                                    </a>                                                </li>                                            <?php endforeach; ?>                                        </ul>                                    </div>                                </div>                            </div>                        </div><!-- ends: .widget-wrapper -->                    <?php endif; ?>                    <?php if (count($related)): ?>                        <!-- popular post -->                        <div class="widget-wrapper">                            <div class="widget-default">                                <div class="widget-header">                                    <h6 class="widget-title aviny">آخرین نوشته‌ها</h6>                                </div>                                <div class="widget-content">                                    <div class="sidebar-post">                                        <?php foreach ($related as $k => $blg): ?>                                            <div class="post-single<?= $k != 0 ? ' border-top pt-4' : ''; ?>">                                                <div class="d-flex align-items-center">                                                    <a href="<?= base_url('blog/detail/' . $blg['slug']); ?>">                                                        <img src="<?= base_url($blg['image']); ?>"                                                             alt="<?= $blg['title']; ?>">                                                    </a>                                                    <p>                                                        <span class="iranyekan-light font-size-sm">                                                            <?= jDateTime::date('j F Y', $blg['created_at']); ?>                                                        </span>                                                        <span class="iranyekan-light font-size-sm">                                                            توسط                                                            <a class="iranyekan-light font-size-sm"                                                               href="<?= base_url('blog/search/writer/' . urlencode($blg['writer'])) ?>">                                                                <?= $blg['writer']; ?>                                                            </a>                                                        </span>                                                    </p>                                                </div>                                                <a href="<?= base_url('blog/detail/' . $blg['slug']); ?>"                                                   class="post-title">                                                    <?= $blg['title']; ?>                                                </a>                                            </div><!-- ends: .post-single -->                                        <?php endforeach; ?>                                    </div>                                </div>                            </div>                        </div><!-- ends: .widget-wrapper -->                    <?php endif; ?>                </div><!-- ends: .sidebar -->            </div><!-- ends: .col-lg-4 -->        </div>    </div></section><!-- ends: .blog-area --><!-- Message modal --><?php if (isset($_SESSION['blog-detail-err'])): ?>    <?php    $this->view('templates/fe/modals/message-modal', [        'class' => 'omega-modal-show',        'id' => 'badBlogMsg',        'icon' => 'la la-exclamation-circle color-warning',        'title' => 'اخطار',        'message' => $_SESSION['blog-detail-err'],        'cancelMessage' => 'باشه'    ]);    unset($_SESSION['blog-detail-err']);    ?><?php endif; ?><!-- End message modal --><?php if (!$auth->isLoggedIn()): ?>    <?php $this->view('templates/fe/login-modal', $data); ?>    <?php $this->view('templates/fe/signup-modal', $data); ?><?php endif; ?><?php $this->view('templates/fe/home-footer-part', $data); ?>