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
                                    class="text-semibold">شیوه‌های ارسال</span>
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
                        <li class="active">شیوه‌های ارسال</li>
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
                                        <h6 class="panel-title">شیوه‌های ارسال</h6>
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
                                                    <th>عنوان</th>
                                                    <th>قیمت</th>
                                                    <th>سقف اعمال هزینه</th>
                                                    <th>حداقل روز کاری</th>
                                                    <th>حداکثر روز کاری</th>
                                                    <th>وضعیت</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load categories data -->
                                                <?php foreach ($shippings as $key => $shipping): ?>
                                                    <tr>
                                                        <td width="50px">
                                                            <?= convertNumbersToPersian($key + 1); ?>
                                                        </td>
                                                        <td>
                                                            <?= $shipping['shipping_title']; ?>
                                                        </td>
                                                        <td>
                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($shipping['shipping_price'], true))); ?>
                                                            تومان
                                                        </td>
                                                        <td>
                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($shipping['max_price'], true))); ?>
                                                            تومان
                                                        </td>
                                                        <td align="center">
                                                            <?php if ($shipping['min_days'] == 0): ?>
                                                                <i class="icon-dash text-danger" aria-hidden="true"></i>
                                                            <?php else: ?>
                                                                <?= convertNumbersToPersian($shipping['min_days']); ?>
                                                                روز کاری
                                                            <?php endif; ?>
                                                        </td>
                                                        <td align="center">
                                                            <?php if ($shipping['max_days'] == 0): ?>
                                                                <i class="icon-dash text-danger" aria-hidden="true"></i>
                                                            <?php else: ?>
                                                                <?= convertNumbersToPersian($shipping['max_days']); ?>
                                                                روز کاری
                                                            <?php endif; ?>
                                                        </td>
                                                        <td align="center">
                                                            <?php if ($shipping['status'] == 1): ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-success">فعال</span>
                                                            <?php else: ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-danger">غیر فعال</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="width: 115px;" class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="text-primary-600">
                                                                    <a href="<?= base_url(); ?>admin/editShipping/<?= $shipping['id']; ?>"
                                                                       title="ویرایش" data-popup="tooltip">
                                                                        <i class="icon-pencil7"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="text-danger-600">
                                                                    <a class="deleteShippingBtn"
                                                                       title="حذف" data-popup="tooltip">
                                                                        <input type="hidden"
                                                                               value="<?= $shipping['id']; ?>">
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