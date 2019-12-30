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
                            <span class="text-semibold">کاربران مسدود شده</span>
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
                        <li class="active">کاربران مسدود شده</li>
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
                                        <h6 class="panel-title">کاربران مسدود شده</h6>
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
                                                    <th>کد ملی</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load categories data -->
                                                <?php foreach ($blocked as $key => $blk): ?>
                                                    <tr>
                                                        <td width="50px">
                                                            <?= convertNumbersToPersian($key + 1); ?>
                                                        </td>
                                                        <td>
                                                            <?= $blk['n_code']; ?>
                                                        </td>
                                                        <td style="width: 115px;" class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="text-danger-600">
                                                                    <a class="deleteBlockedUserBtn"
                                                                       title="حذف" data-popup="tooltip">
                                                                        <input type="hidden"
                                                                               value="<?= $blk['id']; ?>">
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