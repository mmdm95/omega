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
                                    class="text-semibold">ویرایش نوشته ثابت</span>
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
                            <a href="<?= base_url(); ?>admin/manageStaticPage">
                                نوشته‌های ثابت
                            </a>
                        </li>
                        <li class="active">افزودن نوشته ثابت</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?= base_url(); ?>admin/editStaticPage/<?= $param[0]; ?>" method="post">
                            <?= $data['form_token']; ?>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">متن نوشته ثابت</h6>
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
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>عنوان نوشته:</label>
                                                <input name="title" type="text" class="form-control"
                                                       placeholder="اجباری"
                                                       value="<?= set_value($spgVals['title'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>آدرس نوشته:</label>
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <input name="url_name" type="text" class="form-control"
                                                               placeholder="اجباری"
                                                               value="<?= set_value($spgVals['url_name'] ?? ''); ?>">
                                                    </div>
                                                    <div class="col-xs-8 ltr mt-10">
                                                        <span class="text-muted border-bottom border-default display-block pb-5">
                                                            www.20tik.ir/pages/
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-20 no-padding-top">
                                                <div class="form-group col-md-12 mt-12">
                                                    <span class="text-danger">*</span>
                                                    <label>متن توضیحات محصول:</label>
                                                    <textarea
                                                            id="cntEditor"
                                                            class="form-control"
                                                            name="body"
                                                            rows="10"><?= set_value($spgVals['body'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="text-right col-md-12">
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
