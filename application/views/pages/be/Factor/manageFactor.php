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
                                    class="text-semibold">سفارشات</span>
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
                        <li class="active">سفارشات</li>
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
                                        <h6 class="panel-title">سفارشات</h6>
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
                                                    <th>طرح</th>
                                                    <th>شماره فاکتور</th>
                                                    <th>تاریخ ثبت فاکتور</th>
                                                    <th>مبلغ پرداخت شده/مبلغ کل</th>
                                                    <th>وضعیت پرداخت</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load categories data -->
                                                <?php foreach ($factors as $key => $factor): ?>
                                                    <tr>
                                                        <td width="50px">
                                                            <?= convertNumbersToPersian($key + 1); ?>
                                                        </td>
                                                        <td>
                                                            <a data-url="<?= base_url($factor['u_image'] ?? PROFILE_DEFAULT_IMAGE); ?>"
                                                               data-popup="lightbox">
                                                                <img src=""
                                                                     data-src="<?= base_url($factor['u_image'] ?? PROFILE_DEFAULT_IMAGE); ?>"
                                                                     alt="<?= $factor['full_name'] ?? $factor['f_full_name']; ?>"
                                                                     class="img-rounded img-lg img-fit img-preview lazy">
                                                            </a>
                                                            <?php if (!empty($factor['u_id'])): ?>
                                                                <a href="<?= base_url('admin/editUser/' . $factor['u_id']); ?>"
                                                                   class="btn-link">
                                                                    <?= $factor['full_name'] ?? $factor['username']; ?>
                                                                </a>
                                                            <?php else: ?>
                                                                <?= $factor['f_full_name'] ?? $factor['f_username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a data-url="<?= base_url($factor['p_image'] ?? PROFILE_DEFAULT_IMAGE); ?>"
                                                               data-popup="lightbox">
                                                                <img src=""
                                                                     data-src="<?= base_url($factor['p_image'] ?? PROFILE_DEFAULT_IMAGE); ?>"
                                                                     alt="<?= $factor['title']; ?>"
                                                                     class="img-rounded img-lg img-fit img-preview lazy">
                                                            </a>
                                                            <?php if (!empty($factor['p_id'])): ?>
                                                                <a href="<?= base_url('event/detail/' . $factor['slug']); ?>"
                                                                   class="btn-link">
                                                                    <?= $factor['title']; ?>
                                                                </a>
                                                            <?php else: ?>
                                                                ناشناخته
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?= $factor['factor_code']; ?>
                                                        </td>
                                                        <td>
                                                            <?= jDateTime::date('j F Y در ساعت H:i', $factor['created_at']); ?>
                                                        </td>
                                                        <td>
                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($factor['payed_amount'], true))); ?>
                                                            تومان
                                                            /
                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($factor['total_amount'], true))); ?>
                                                            تومان
                                                        </td>
                                                        <td align="center">
                                                            <?php if (!empty($factor['payed_amount'])): ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-success">
                                                                    پرداخت شده
                                                                </span>
                                                            <?php elseif ($factor['payment_status']): ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-danger">
                                                                    پرداخت نشده
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="width: 115px;" class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="text-black">
                                                                    <a href="<?= base_url(); ?>admin/viewFactor/<?= $factor['id']; ?>"
                                                                       title="مشاهده" data-popup="tooltip">
                                                                        <i class="icon-eye"></i>
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