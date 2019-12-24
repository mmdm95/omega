<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

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
                        <li class="active">نظرات</li>
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
                            <div class="col-sm-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">نظرات</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered datatable-highlight">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>کاربر</th>
                                                    <th>تصویر محصول</th>
                                                    <th>عنوان محصول</th>
                                                    <th>تاریخ ارسال نظر</th>
                                                    <th>وضعیت نظر</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load categories data -->
                                                <?php foreach ($comments as $key => $comment): ?>
                                                    <tr>
                                                        <td width="50px">
                                                            <?= convertNumbersToPersian($key + 1); ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!$comment['first_name'] && !$comment['last_name']): ?>
                                                                <?= $comment['first_name'] . ' ' . $comment['last_name']; ?>
                                                            <?php else: ?>
                                                                <?= is_numeric($comment['username']) && mb_strlen($comment['username']) == 11 ?
                                                                    convertNumbersToPersian($comment['username']) :
                                                                    $comment['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a data-url="<?= base_url() . $comment['image']; ?>"
                                                               data-popup="lightbox">
                                                                <img src=""
                                                                     data-src="<?= base_url() . $comment['image']; ?>"
                                                                     alt="<?= $comment['product_title']; ?>"
                                                                     class="img-rounded img-preview lazy">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="<?= base_url('product/' . $comment['product_code'] . '/' . url_title($comment['product_title'])); ?>"
                                                               target="_blank">
                                                                <?= $comment['product_title']; ?>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <?= jDateTime::date('Y/m/d - H:i', $comment['comment_date']); ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($comment['status'] == 2): ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-success">تایید شده</span>
                                                            <?php elseif ($comment['status'] == 1): ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-info">مشاهده شده</span>
                                                            <?php else: ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-danger">مشاهده نشده</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="width: 115px;" class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="text-black">
                                                                    <a href="<?= base_url('admin/viewComment/' . $comment['id']); ?>"
                                                                       title="مشاهده" data-popup="tooltip">
                                                                        <i class="icon-eye"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="text-danger-600">
                                                                    <a class="deleteCommentBtn"
                                                                       title="حذف" data-popup="tooltip">
                                                                        <input type="hidden"
                                                                               value="<?= $comment['id']; ?>">
                                                                        <i class="icon-trash"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /form centered -->
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