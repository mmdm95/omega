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
                                    class="text-semibold">افزودن روش پرداخت</span>
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
                            <a href="<?= base_url(); ?>admin/managePayment">
                                روش‌های پرداخت
                            </a>
                        </li>
                        <li class="active">افزودن روش پرداخت</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?= base_url(); ?>admin/addPayment" method="post">
                            <?= $data['form_token']; ?>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">مشخصات روش پرداخت</h6>
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

                                            <div class="col-lg-12 mb-15">
                                                <div class="help-block alert alert-info no-border-right no-border-top no-border-bottom border-lg p-10">
                                                    ابعاد تصویر بهتر است با هم برابر باشد. مانند
                                                    <span class="display-inline-block ltr">
                                                        <code>100px * 100px</code>
                                                    </span>
                                                </div>
                                                <div class="cursor-pointer pick-file border border-lg border-default"
                                                     data-toggle="modal"
                                                     data-target="#modal_full"
                                                     style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                    <input class="image-file" type="hidden"
                                                           name="image"
                                                           value="<?= set_value($payVals['image'] ?? ''); ?>">
                                                    <div class="media stack-media-on-mobile">
                                                        <div class="media-left">
                                                            <div class="thumb">
                                                                <a class="display-inline-block"
                                                                   style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                    <img
                                                                            src="<?= set_value($payVals['image'] ?? '', '', base_url($payVals['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                            class="img-rounded" alt=""
                                                                            style="width: 100px; height: 100px; object-fit: contain;"
                                                                            data-base-url="<?= base_url(); ?>">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="media-heading">
                                                                <a class="text-grey-300">
                                                                    <span class="text-danger">*</span>
                                                                    انتخاب تصویر:
                                                                </a>
                                                                <a class="io-image-name display-block">
                                                                    <?= basename(set_value($payVals['image'] ?? '')); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                        <small class="clear-img-val">&times;</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <span class="text-danger">*</span>
                                                <label>عنوان:</label>
                                                <input name="title" type="text" class="form-control"
                                                       placeholder="اجباری"
                                                       value="<?= set_value($payVals['title'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group col-lg-12 text-right">
                                                <label for="payStatus">وضعیت نمایش:</label>
                                                <input type="checkbox" name="status" id="payStatus"
                                                       class="switchery" <?= set_value($payVals['status'] ?? '', 'off', '', 'checked', '=='); ?> />
                                            </div>


                                            <div class="text-right col-md-12 mt-20">
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

                <!-- Standard width modal -->
                <div id="modal_full" class="modal fade lazyContainer">
                    <div class="modal-dialog modal-full">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title">
                                    انتخاب فایل
                                </h5>
                            </div>

                            <div id="files-body" class="modal-body">
                                <?php $this->view("templates/be/efm-view", $data); ?>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <i class="icon-cancel-circle2 position-left" aria-hidden="true"></i>
                                    لغو
                                </button>
                                <button id="file-ok" type="button" class="btn btn-primary" data-dismiss="modal">
                                    <i class="icon-checkmark-circle position-left" aria-hidden="true"></i>
                                    انتخاب
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /standard width modal -->

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