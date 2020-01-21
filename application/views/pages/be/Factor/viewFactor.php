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
                            <i class="icon-circle position-left"></i>
                            <span class="text-semibold">
                                جزئیات سفارش به کد
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
                                        <h6 class="panel-title">تغییر وضعیت ارسال</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12 p-15">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <?php if (isset($errors) && count($errors)): ?>
                                                        <div class="alert alert-danger alert-styled-left alert-bordered
                                                                    no-border-top no-border-right no-border-bottom">
                                                            <ul class="list-unstyled">
                                                                <?php foreach ($errors as $err): ?>
                                                                    <li>
                                                                        <i class="icon-dash" aria-hidden="true"></i>
                                                                        <?= $err; ?>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php elseif (isset($success)): ?>
                                                        <div class="alert alert-success alert-styled-left alert-bordered
                                                                    no-border-top no-border-right no-border-bottom">
                                                            <p>
                                                                <?= $success; ?>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <form action="<?= base_url('admin/viewFactor/' . $factor['id']); ?>"
                                                  method="post">
                                                <?= $form_token; ?>

                                                <div class="form-group">
                                                    <select class="select-no-search" name="send-status">
                                                        <?php foreach ($allSendStatus as $key => $status): ?>
                                                            <option value="<?= $status['id']; ?>"
                                                                    class="<?= $status['badge']; ?>"
                                                                <?= set_value($factor['send_status'] ?? '', $status['id'], 'selected', '', '=='); ?>>
                                                                <?= $status['name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary">
                                                        تغییر وضعیت ارسال
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

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
                                                    وضعیت ارسال
                                                </strong>
                                            </div>
                                            <div class="col-md-12 text-center p-15 border border-grey-300 text-white <?= $factorStatus['badge']; ?>">
                                                <h6 class="no-margin">
                                                    <strong>
                                                        <?= $factorStatus['name']; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                        </div>

                                        <div class="row mt-20 mb-20"></div>

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
                                                        کد رهگیری :
                                                    </small>
                                                    <strong>
                                                        <?php if (isset($factor['payment_info']['payment_code'])): ?>
                                                            <?= $factor['payment_info']['payment_code']; ?>
                                                        <?php else: ?>
                                                            <i class="icon-dash text-danger"></i>
                                                        <?php endif; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <?php if ($factor['payment_status'] == OWN_PAYMENT_STATUS_SUCCESSFUL): ?>
                                                <div class="col-md-12 text-center p-15 border border-grey-300 alert-success">
                                                    <h6 class="no-margin">
                                                        <small class="text-grey-800">
                                                            وضعیت پرداخت :
                                                        </small>
                                                        <strong>
                                                            موفق
                                                        </strong>
                                                    </h6>
                                                </div>
                                            <?php elseif ($factor['payment_status'] == OWN_PAYMENT_STATUS_FAILED): ?>
                                                <div class="col-md-12 text-center p-15 border border-grey-300 alert-danger">
                                                    <h6 class="no-margin">
                                                        <small class="text-grey-800">
                                                            وضعیت پرداخت :
                                                        </small>
                                                        <strong>
                                                            ناموفق
                                                        </strong>
                                                    </h6>
                                                </div>
                                            <?php elseif ($factor['payment_status'] == OWN_PAYMENT_STATUS_NOT_PAYED): ?>
                                                <div class="col-md-12 text-center p-15 border border-grey-300 alert-warning">
                                                    <h6 class="no-margin">
                                                        <small class="text-grey-800">
                                                            وضعیت پرداخت :
                                                        </small>
                                                        <strong>
                                                            در انتظار پرداخت
                                                        </strong>
                                                    </h6>
                                                </div>
                                            <?php elseif ($factor['payment_status'] == OWN_PAYMENT_STATUS_WAIT): ?>
                                                <div class="col-md-12 text-center p-15 border border-grey-300 alert-info">
                                                    <h6 class="no-margin">
                                                        <small class="text-grey-800">
                                                            وضعیت پرداخت :
                                                        </small>
                                                        <strong>
                                                            <?= $factor['payment_title']; ?>
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
                                                            نامشخص
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
                                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($factor['amount'], true))); ?>
                                                        تومان
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        مبلغ تخفیف :
                                                    </small>
                                                    <strong>
                                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($factor['discount_price'], true))); ?>
                                                        تومان
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        هزینه ارسال :
                                                    </small>
                                                    <strong>
                                                        <?php if ($factor['shipping_price'] != 0): ?>
                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($factor['shipping_price'], true))); ?>
                                                            تومان
                                                        <?php else: ?>
                                                            رایگان
                                                        <?php endif; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300 alert-info">
                                                <h6 class="no-margin">
                                                    <label class="no-margin">
                                                        <?php if ($factor['want_factor'] == 1): ?>
                                                            درخواست فاکتور
                                                        <?php else: ?>
                                                            عدم درخواست فاکتور
                                                        <?php endif; ?>
                                                    </label>
                                                </h6>
                                            </div>
                                            <div class="col-md-12 text-center p-15 border border-grey-300"
                                                 style="background-color: #e7e7e7;">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        مبلغ قابل پرداخت :
                                                    </small>
                                                    <strong>
                                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($factor['final_amount'], true))); ?>
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
                                                        <?= $factor['first_name'] . ' ' . $factor['last_name']; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        شماره موبایل :
                                                    </small>
                                                    <strong>
                                                        <?= convertNumbersToPersian($factor['mobile']); ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                        </div>

                                        <div class="row mt-20 mb-20"></div>

                                        <div class="row mb-20">
                                            <div class="col-sm-12 mb-5 text-teal">
                                                <strong>
                                                    <i class="icon-circle-left2 mr-5"></i>
                                                    مشخصات گیرنده سفارش
                                                </strong>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        شماره تماس :
                                                    </small>
                                                    <strong>
                                                        <?= $factor['shipping_phone']; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        استان :
                                                    </small>
                                                    <strong>
                                                        <?= $factor['shipping_province']; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        شهر :
                                                    </small>
                                                    <strong>
                                                        <?= $factor['shipping_city']; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-6 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        کد پستی :
                                                    </small>
                                                    <strong>
                                                        <?= convertNumbersToPersian($factor['shipping_postal_code']); ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div class="col-md-12 text-center p-15 border border-grey-300">
                                                <h6 class="no-margin">
                                                    <small class="text-grey-800">
                                                        آدرس :
                                                    </small>
                                                    <strong>
                                                        <?= $factor['shipping_address']; ?>
                                                    </strong>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">محصولات خریداری شده</h6>
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
                                                    <th>تصویر</th>
                                                    <th>نام محصول</th>
                                                    <th>رنگ</th>
                                                    <th>تعداد</th>
                                                    <th>قیمت ورودی</th>
                                                    <th>مبلغ کل</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load users data -->
                                                <?php foreach ($factorItems as $key => $item): ?>
                                                    <tr>
                                                        <td><?= convertNumbersToPersian(($key + 1)); ?></td>
                                                        <td width="120px">
                                                            <a data-url="<?= base_url() . $item['image']; ?>"
                                                               data-popup="lightbox">
                                                                <img src=""
                                                                     data-src="<?= base_url() . $item['image']; ?>"
                                                                     alt="تصویر محصول"
                                                                     class="img-rounded img-preview lazy img-responsive">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <?= $item['product_title'] ?: '<i class="fa fa-minus text-danger"></i>'; ?>
                                                        </td>
                                                        <td>
                                                            <?= $item['product_color']; ?>
                                                        </td>
                                                        <td>
                                                            <?= convertNumbersToPersian($item['product_count']); ?>
                                                        </td>
                                                        <td>
                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($item['product_unit_price'], true))); ?>
                                                            تومان
                                                        </td>
                                                        <td>
                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($item['product_price'], true))); ?>
                                                            تومان
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