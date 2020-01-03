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
                                class="text-semibold">رنگ‌ها</span>
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
                        <li class="active">رنگ‌ها</li>
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
                                        <h6 class="panel-title">رنگ‌ها</h6>
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
                                                    <th>رنگ</th>
                                                    <th>نام رنگ</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load categories data -->
                                                <?php foreach ($colors as $key => $color): ?>
                                                    <tr>
                                                        <td width="50px">
                                                            <?= convertNumbersToPersian($key + 1); ?>
                                                        </td>
                                                        <td width="100px" align="center">
                                                            <span class="img-xs img-rounded display-inline-block shadow-depth2"
                                                                  style="background-color: <?= $color['color_hex']; ?>; border: 1px solid #eee;"></span>
                                                            <span class="display-block ltr mt-5 text-size-mini"><?= $color['color_hex']; ?></span>
                                                        </td>
                                                        <td>
                                                            <?= $color['color_name']; ?>
                                                        </td>
                                                        <td style="width: 115px;" class="text-center">
                                                            <?php if ($color['deletable'] == 1): ?>
                                                                <ul class="icons-list">
                                                                    <li class="text-primary-600">
                                                                        <a href="<?= base_url(); ?>admin/editColor/<?= $color['id']; ?>"
                                                                           title="ویرایش" data-popup="tooltip">
                                                                            <i class="icon-pencil7"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li class="text-danger-600">
                                                                        <a class="deleteColorBtn"
                                                                           title="حذف" data-popup="tooltip">
                                                                            <input type="hidden"
                                                                                   value="<?= $color['id']; ?>">
                                                                            <i class="icon-trash"></i>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            <?php else: ?>
                                                                <i class="icon-minus2 text-grey-300"></i>
                                                            <?php endif; ?>
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