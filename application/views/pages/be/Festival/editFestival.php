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
                                ویرایش جشنواره
                                -
                                <?= $fesVals['festival_title']; ?>
                            </span>
                        </h5>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?= base_url(); ?>admin/index"><i class="icon-home2 position-left"></i>
                                داشبورد
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/manageFestival">
                                جشنواره‌ها
                            </a>
                        </li>
                        <li class="active">ویرایش جشنواره</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?= base_url(); ?>admin/editFestival/<?= $param[0]; ?>" method="post">
                            <?= $data['form_token']; ?>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">مشخصات جشنواره</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
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
                                            <?php if (isset($warning)): ?>
                                                <div class="alert alert-warning alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <p>
                                                        <?= $warning; ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>

                                            <div class="form-group col-lg-9">
                                                <span class="text-danger">*</span>
                                                <label>عنوان جشنواره:</label>
                                                <input name="title" type="text" class="form-control"
                                                       placeholder="اجباری"
                                                       value="<?= set_value($fesVals['festival_title'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ شروع جشنواره:</label>
                                                <input type="hidden" name="set" id="altDateFieldSet">
                                                <input type="text" class="form-control range-from"
                                                       placeholder="تاریخ شروع" readonly data-time="true"
                                                       data-alt-field="#altDateFieldSet" data-format="YYYY/MM/DD - HH:mm"
                                                       value="<?= set_value(date('Y/m/d H:i', $fesVals['festival_set_date']) ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ پایان جشنواره:</label>
                                                <input type="hidden" name="expire" id="altDateFieldExpire">
                                                <input type="text" class="form-control range-to"
                                                       placeholder="تاریخ انقضا" readonly data-time="true"
                                                       data-alt-field="#altDateFieldExpire" data-format="YYYY/MM/DD - HH:mm"
                                                       value="<?= set_value(date('Y/m/d H:i', $fesVals['festival_expire_date']) ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-12 text-right mt-20">
                                                <label for="catStatus">وضعیت جشنواره:</label>
                                                <input type="checkbox" name="status" id="catStatus"
                                                       class="switchery" <?= set_value($fesVals['status'] ?? '', 1, 'checked', '', '=='); ?> />
                                            </div>

                                            <div class="text-right col-md-12 mt-20">
                                                <a href="<?= base_url('admin/manageFestival'); ?>" class="btn btn-default mr-5">
                                                    بازگشت
                                                </a>
                                                <button type="submit" class="btn btn-primary submit-button">
                                                    ذخیره
                                                    <i class="icon-arrow-left12 position-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer -->
                <?php $this->view("templates/be/copyright", $data); ?>
                <!-- /footer -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->