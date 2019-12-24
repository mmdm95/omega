<!-- Main navbar -->
<?php $this->view("templates/be/mainnavbar", $data); ?>
<!-- /main navbar -->
<!-- Page container -->
<div class="page-container">
    <!-- Page content -->
    <div class="page-content">
        <input type="hidden" id="BASE_URL" value="<?= base_url(); ?>">

        <!-- Main sidebar -->
        <?php $this->view("templates/be/mainsidebar", $data); ?>
        <!-- /main sidebar -->
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Page header -->
            <div class="page-header page-header-default"
                 style="border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                <div class="page-header-content border-bottom border-bottom-success">
                    <div class="page-title">
                        <h5>
                            <i class="icon-circle position-left"></i> <span
                                    class="text-semibold">نظرات</span>
                        </h5>
                    </div>
                </div>
                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?= base_url(); ?>admin/index">
                                <i class="icon-home2 position-left"></i>
                                داشبورد
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/manageComment">
                                مدیریت نظرات
                            </a>
                        </li>
                        <li class="active">مشاهده نظر</li>
                    </ul>
                </div>
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title ">مشاهده نظر</h6>
                                        <div class="heading-elements">
                                            <?php if ($comment['status'] < 2): ?>
                                                <a href="javascript:void(0);"
                                                   id="acceptCommentBtn"
                                                   class="btn btn-default btn-rounded heading-btn-group border-success-600 text-success-600 p-10"
                                                   title="تایید" data-popup="tooltip">
                                                    <input type="hidden" value="<?= $comment['id']; ?>">
                                                    <i class="icon-check" aria-hidden="true"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($comment['status'] == 2): ?>
                                                <a href="javascript:void(0);"
                                                   id="declineCommentBtn"
                                                   class="btn btn-default btn-rounded heading-btn-group border-orange-600 text-orange-600 p-10"
                                                   title="عدم تأیید" data-popup="tooltip">
                                                    <input type="hidden" value="<?= $comment['id']; ?>">
                                                    <i class="icon-cross2" aria-hidden="true"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a id="delCommentBtn"
                                               class="btn btn-default btn-rounded heading-btn-group border-danger-600 text-danger-600 p-10"
                                               title="حذف" data-popup="tooltip">
                                                <input type="hidden" value="<?= $comment['id']; ?>">
                                                <i class="icon-trash" aria-hidden="true"></i>
                                            </a>
                                        </div>

                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>
                                                    <i class="icon-envelop5 position-left bg-orange btn-rounded p-10"
                                                       style="width: 45px; height: 45px; font-size: 24px;"></i>
                                                    <a href="" class="display-inline-block">
                                                        <div class="text-small display-inline-block">
                                                            <?php if ($comment['status'] == 1): ?>
                                                                <span class="label label-primary">
                                                                    در حال بررسی
                                                                </span>
                                                            <?php elseif ($comment['status'] == 2): ?>
                                                                <span class="label label-success">
                                                                    تایید شده
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <?php if (!empty($comment['first_name']) || !empty($comment['last_name'])): ?>
                                                                <?= $comment['first_name'] . ' ' . $comment['last_name']; ?>
                                                            <?php else: ?>
                                                                <?= $comment['username']; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </a>
                                                    <span class="text-muted text-small display-inline-block">
                                                        <i class="icon-dash" aria-hidden="true"></i>
                                                        <?= jDateTime::date('j F Y', $comment['comment_date']); ?>
                                                    </span>
                                                </h5>
                                            </div>
                                            <div class="col-md-12 mt-15"></div>
                                            
                                            <!-- Product-->
                                            <div class="col-lg-6 col-md-6">
                                                <div class="panel panel-body">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <a href="<?= base_url($comment['image']); ?>"
                                                               data-popup="lightbox">
                                                                <img src="<?= base_url($comment['image']); ?>"
                                                                     style="width: 70px; height: 70px;"
                                                                     class="img-circle" alt="">
                                                            </a>
                                                        </div>

                                                        <div class="media-body">
                                                            <h6 class="media-heading">
                                                                <a href="<?= base_url('product/' . $comment['product_code'] . '/' . url_title($comment['product_title'])); ?>"
                                                                   target="_blank">
                                                                    <?= $comment['product_title']; ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- User-->
                                            <div class="col-lg-6 col-md-6">
                                                <div class="panel panel-body">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <a href="<?= asset_url('fe/img/avatars/' . $comment['u_image']); ?>"
                                                               data-popup="lightbox">
                                                                <img src="<?= asset_url('fe/img/avatars/' . $comment['u_image']); ?>"
                                                                     style="width: 70px; height: 70px;"
                                                                     class="img-circle" alt="">
                                                            </a>
                                                        </div>

                                                        <div class="media-body">
                                                            <h6 class="media-heading">
                                                                <?= $comment['username']; ?>
                                                            </h6>
                                                            <p class="text-muted">
                                                                <?= $comment['first_name'] . ' ' . $comment['last_name']; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12"></div>

                                            <?php
                                            $pros = array_map('trim', explode(',', $comment['pros']));
                                            $pros = array_filter($pros, function ($p) {
                                                return !empty($p);
                                            });
                                            $cons = array_map('trim', explode(',', $comment['cons']));
                                            $cons = array_filter($cons, function ($c) {
                                                return !empty($c);
                                            });
                                            ?>
                                            <div class="col-lg-6 col-md-12">
                                                <h6>
                                                    <i class="icon-thumbs-up2 position-left bg-info btn-rounded p-10"
                                                       style="width: 45px; height: 45px; font-size: 24px;"></i>
                                                    <span class="text-bold text-primary">
                                                        <?= convertNumbersToPersian($comment['helpful']); ?>
                                                    </span>
                                                    <small>
                                                        نفر این نظر را مفید می‌دانند
                                                    </small>
                                                </h6>
                                                <div class="col-lg-12 alert-info jumbotron pr-20 pl-20">
                                                    <?php if (count($pros)): ?>
                                                        <ul class="list-unstyled">
                                                            <?php foreach ($pros as $p): ?>
                                                                <li>
                                                                    <i class="icon-circle-small text-info"></i>
                                                                    <?= $p; ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        <p class="text-muted">
                                                            موردی ثبت نشده است
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <h6>
                                                    <i class="icon-thumbs-down2 position-left bg-danger btn-rounded p-10"
                                                       style="width: 45px; height: 45px; font-size: 24px;"></i>
                                                    <span class="text-bold text-danger">
                                                        <?= convertNumbersToPersian($comment['useless']); ?>
                                                    </span>
                                                    <small>
                                                        نفر این نظر را مفید نمی‌دانند
                                                    </small>
                                                </h6>
                                                <div class="col-lg-12 alert-danger jumbotron pr-20 pl-20">
                                                    <?php if (count($cons)): ?>
                                                        <ul class="list-unstyled">
                                                            <?php foreach ($cons as $c): ?>
                                                                <li>
                                                                    <i class="icon-circle-small text-danger"></i>
                                                                    <?= $c; ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        <p class="text-muted">
                                                            موردی ثبت نشده است
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-lg-12 jumbotron pr-20 pl-20">
                                                    <p class="text-black text-light"
                                                       style="font-size: 15px; line-height: 26px;">
                                                        <?= nl2br($comment['body']); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <?php $this->view("templates/be/copyright", $data); ?>
                <!-- /footer -->
            </div>
            <!-- /content area -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->