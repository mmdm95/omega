<?php defined('BASE_PATH') OR exit('No direct script access allowed');

use HPayment\Payment; ?>

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
                            <i class="icon-circle position-left"></i>
                            <span class="text-semibold">
                                جزئیات سفارش به شماره فاکتور
                                <?= $factor['factor_code']; ?>
                            </span>
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
                            <a href="<?= base_url(); ?>admin/manageFactor">
                                نمایش سفارشات
                            </a>
                        </li>
                        <li class="active">جزئیات سفارش</li>
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
                                        <h6 class="panel-title">مشخصات سفارش</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row mb-20">
                                            <div class="col-sm-12 mb-5 text-teal">
                                                <strong>
                                                    <i class="icon-circle-left2 mr-5"></i>
                                                    مشخصات پرداخت
                                                </strong>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300 text-primary">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        کد فاکتور :
                                                    </small>
                                                    <strong>
                                                        <?= $factor['factor_code']; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300 alert-primary">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        مبلغ باقی‌مانده :
                                                    </small>
                                                    <strong>
                                                        <?= convertNumbersToPersian(number_format((int)convertNumbersToPersian($factor['total_amount'], true) - (int)convertNumbersToPersian($factor['payed_amount'], true))); ?>
                                                        تومان
                                                    </strong>
                                                </h6>
                                            </div>
                                            <?php if (!empty($factor['payed_amount'])): ?>
                                                <div class="col-md-12 text-center p-15 border border-grey-300 alert-success">
                                                    <h6 class="no-margin">
                                                        <small class="text-grey-800">
                                                            وضعیت پرداخت :
                                                        </small>
                                                        <strong>
                                                            پرداخت شده
                                                        </strong>
                                                    </h6>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-md-12 text-center p-15 border border-grey-300"
                                                     style="background-color: #f6f6f6;">
                                                    <h6 class="no-margin">
                                                        <small class="text-grey-800">
                                                            وضعیت پرداخت:
                                                        </small>
                                                        <strong>
                                                            پرداخت نشده
                                                        </strong>
                                                    </h6>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        مبلغ کل :
                                                    </small>
                                                    <strong>
                                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($factor['total_amount'], true))); ?>
                                                        تومان
                                                    </strong>
                                                </h6>
                                            </div>
                                        </div>

                                        <div class="row mt-20 mb-20"></div>

                                        <div class="row mb-20">
                                            <div class="col-sm-12 mb-5 text-teal">
                                                <strong>
                                                    <i class="icon-circle-left2 mr-5"></i>
                                                    مشخصات ثبت کننده سفارش
                                                </strong>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        نام و نام خانوادگی :
                                                    </small>
                                                    <strong>
                                                        <?= $factor['f_full_name']; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        شماره موبایل/نام کاربری :
                                                    </small>
                                                    <strong>
                                                        <?php if (mb_strlen($factor['f_username']) == 11): ?>
                                                            <?= convertNumbersToPersian($factor['f_username']); ?>
                                                        <?php else: ?>
                                                            <?= $factor['f_username']; ?>
                                                        <?php endif; ?>
                                                    </strong>
                                                </h6>
                                            </div>

                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        کاربر ثبت کننده :
                                                    </small>
                                                    <strong>
                                                        <?php if (!empty($factor['u_id'])): ?>
                                                            <a href="<?= base_url('admin/editUser/' . $factor['u_id']); ?>">
                                                                <img src="<?= base_url($factor['u_image']); ?>"
                                                                     alt="<?= $factor['full_name'] ?? $factor['f_full_name']; ?>"
                                                                     class="img-lg img-fit mr-15">

                                                                <?php if (mb_strlen($factor['f_username']) == 11): ?>
                                                                    <?= convertNumbersToPersian($factor['f_username']); ?>
                                                                <?php else: ?>
                                                                    <?= $factor['f_username']; ?>
                                                                <?php endif; ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <img src="<?= base_url(PROFILE_DEFAULT_IMAGE); ?>"
                                                                 alt="<?= $factor['full_name'] ?? $factor['f_full_name']; ?>"
                                                                 class="img-lg img-fit mr-15">

                                                            <?php if (mb_strlen($factor['f_username']) == 11): ?>
                                                                <?= convertNumbersToPersian($factor['f_username']); ?>
                                                            <?php else: ?>
                                                                <?= $factor['f_username']; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </strong>
                                                </h6>
                                            </div>

                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        طرح ثبت شده :
                                                    </small>
                                                    <strong>
                                                        <a href="<?= base_url('event/detail/' . $factor['slug']); ?>">
                                                            <img src="<?= base_url($factor['p_image'] ?? PROFILE_DEFAULT_IMAGE); ?>"
                                                                 alt="<?= $factor['title'] ?? $factor['title']; ?>"
                                                                 class="img-lg img-fit mr-15">

                                                            <?= $factor['title']; ?>
                                                        </a>
                                                    </strong>
                                                </h6>
                                            </div>
                                        </div>

                                        <div class="row mt-20 mb-20"></div>
                                    </div>
                                </div>

                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">پرداخت‌های انجام شده</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr class="bg-primary">
                                                    <th>#</th>
                                                    <th>شماره پیگیری</th>
                                                    <th>وضعیت پرداخت</th>
                                                    <th>مبلغ پرداخت شده</th>
                                                    <th>تاریخ پرداخت</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load users data -->
                                                <?php foreach ($factor['payment'] as $key => $payment): ?>
                                                    <tr>
                                                        <td><?= convertNumbersToPersian(($key + 1)); ?></td>
                                                        <td class="info">
                                                            <?= !empty($payment['payment_code']) ? $payment['payment_code'] : '<i class="icon-minus2"></i>'; ?>
                                                        </td>
                                                        <?php
                                                        if ($payment['payment_status'] == Payment::PAYMENT_TRANSACTION_SUCCESS_ZARINPAL) {
                                                            $payType = 'success';
                                                        } elseif ($payment['payment_status'] == Payment::PAYMENT_TRANSACTION_CANCELED_ZARINPAL) {
                                                            $payType = 'warning';
                                                        } else {
                                                            $payType = 'danger';
                                                        }
                                                        ?>
                                                        <td class="<?= $payType; ?>">
                                                            <?php if ($payment['payment_status'] == Payment::PAYMENT_TRANSACTION_SUCCESS_ZARINPAL): ?>
                                                                تراکنش انجام شده
                                                            <?php elseif ($payment['payment_status'] == Payment::PAYMENT_TRANSACTION_CANCELED_ZARINPAL): ?>
                                                                تراکنش لغو شده
                                                            <?php elseif ($payment['payment_status'] == Payment::PAYMENT_TRANSACTION_FAILED_ZARINPAL): ?>
                                                                تراکنش ناموفق بوده
                                                            <?php elseif(!empty($paymentClass->get_message($payment['payment_status']))): ?>
                                                                <?= $paymentClass->get_message($payment['payment_status']); ?>
                                                            <?php else: ?>
                                                                پرداخت انجام نشده
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($payment['amount'])): ?>
                                                                <?= convertNumbersToPersian(number_format(convertNumbersToPersian($payment['amount'], true))); ?>
                                                                تومان
                                                            <?php else: ?>
                                                                <i class="icon-minus2 text-danger"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?= jDateTime::date('j F Y در ساعت H:i', $payment['payment_date']); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">آپشن‌های طرح</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <?php foreach ($factor['f_options'] as $key => $option): ?>
                                            <div class="pl-20 pr-20 pt-5 pb-5 bg-slate-400">
                                                <h4 class="iranyekan-regular m-0">
                                                    <?= $option['title']; ?>
                                                </h4>
                                            </div>

                                            <?php
                                            $isRadio = $option['radio'] == 2 ? true : false;
                                            ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered mb-20">
                                                    <thead>
                                                    <tr class="bg-default">
                                                        <th style="border: 1px solid #ddd;"><strong>عنوان و
                                                                توضیح</strong></th>
                                                        <th style="border: 1px solid #ddd;"><strong>هزینه</strong></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($option['name'] as $k2 => $name): ?>
                                                        <tr>
                                                            <td>
                                                                <h4><?= $name; ?></h4>
                                                                <?php if (!empty($option['desc'][$k2])): ?>
                                                                    <p class="no-margin">
                                                                        <?= $option['desc'][$k2]; ?>
                                                                    </p>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td width="35%">
                                                                <?php if (is_numeric($option['price'][$k2])): ?>
                                                                    <?= convertNumbersToPersian(number_format(convertNumbersToPersian($option['price'][$k2], true))); ?>
                                                                    تومان
                                                                <?php else: ?>
                                                                    <?= $option['price'][$k2]; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endforeach; ?>
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