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
                                    class="text-semibold">افزودن نوشته</span>
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
                            <a href="<?= base_url(); ?>admin/manageArticle">
                                نوشته‌ها
                            </a>
                        </li>
                        <li class="active">افزودن نوشته جدید</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?= base_url(); ?>admin/addArticle" method="post">
                            <?= $data['form_token']; ?>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">مشخصات نوشته</h6>
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

                                            <div class="form-group col-lg-8">
                                                <span class="text-danger">*</span>
                                                <label>عنوان نوشته:</label>
                                                <input name="title" type="text" class="form-control"
                                                       placeholder="اجباری"
                                                       value="<?= set_value($atcVals['title'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <span class="text-danger">*</span>
                                                <label>دسته‌بندی والد:</label>
                                                <select class="select-rtl" name="category">
                                                    <?php foreach ($categories as $key => $category): ?>
                                                        <option value="<?= $category['id']; ?>"
                                                            <?= set_value($atcVals['category'] ?? '', $category['id'], 'selected', '', '=='); ?>>
                                                            <?= $category['fa_slug']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <label>کلمات کلیدی:</label>
                                                <input name="keywords" type="text"
                                                       class="form-control" placeholder="Press Enter"
                                                       data-role="tagsinput"
                                                       value="<?= set_value($atcVals['keywords'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-12 text-right">
                                                <label for="catStatus">وضعیت انتشار:</label>
                                                <input type="checkbox" name="publish" id="catStatus"
                                                       class="switchery" <?= set_value($atcVals['publish'] ?? '', 'off', '', 'checked', '=='); ?> />
                                            </div>
                                            <div class="row pt-20 no-padding-top">
                                                <div class="form-group col-md-12 mt-12">
                                                    <span class="text-danger">*</span>
                                                    <label>متن توضیحات:</label>
                                                    <textarea
                                                            id="cntEditor"
                                                            class="form-control"
                                                            name="body"
                                                            rows="10"><?= set_value($atcVals['body'] ?? ''); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="text-right col-md-12 mt-20">
                                                <a href="<?= base_url('admin/manageArticle'); ?>"
                                                   class="btn btn-default mr-5">
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