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
                                    class="text-semibold">مدیریت جشنواره</span>
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
                            <a href="<?= base_url(); ?>admin/manageFestival">
                                جشنواره‌ها
                            </a>
                        </li>
                        <li class="active"><?= $festival; ?></li>
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
                            <div class="col-lg-4 col-lg-push-8">
                                <form action="<?= base_url(); ?>admin/festivalProduct/<?= $param[0]; ?>" method="post">
                                    <?= $data['form_token']; ?>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">افزودن محصول به جشنواره</h6>
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

                                            <div class="form-group col-md-7 col-lg-12">
                                                <span class="text-danger">*</span>
                                                <label>انتخاب محصول:</label>
                                                <select class="select-rtl" name="product">
                                                    <option value="0" selected disabled>انتخاب کنید</option>
                                                    <?php foreach ($products as $key => $product): ?>
                                                        <option value="<?= $product['id']; ?>"
                                                            <?= set_value($fesPro['product'] ?? '', $product['id'], 'selected', '', '=='); ?>>
                                                            <?= $product['product_title']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-5 col-lg-12">
                                                <span class="text-danger">*</span>
                                                <label>درصد تخفیف:</label>
                                                <input name="discount" type="text" class="form-control"
                                                       placeholder="عددی بین ۰ و ۱۰۰"
                                                       value="<?= set_value($fesPro['discount'] ?? ''); ?>">
                                            </div>

                                            <div class="text-right col-md-12 mt-20">
                                                <button type="submit" class="btn btn-primary submit-button">
                                                    ذخیره
                                                    <i class="icon-arrow-left12 position-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-8 col-lg-pull-4">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><?= $festival; ?></h6>
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
                                                    <th>تصویر محصول</th>
                                                    <th>عنوان محصول</th>
                                                    <th>تخفیف</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load categories data -->
                                                <?php foreach ($festivalProducts as $key => $product): ?>
                                                    <tr>
                                                        <td width="50px">
                                                            <?= convertNumbersToPersian($key + 1); ?>
                                                        </td>
                                                        <td width="100px">
                                                            <a data-url="<?= base_url() . $product['image']; ?>"
                                                               data-popup="lightbox">
                                                                <img src=""
                                                                     data-src="<?= base_url() . $product['image']; ?>"
                                                                     alt="<?= $product['product_title']; ?>"
                                                                     class="img-rounded img-preview lazy">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="<?= base_url('product/' . $product['product_code'] . '/' . url_title($product['product_title'])); ?>">
                                                                <?= $product['product_title']; ?>
                                                                <span class="text-muted display-block text-size-small mt-5">
                                                                    <?= $product['latin_title'] ?? "<i class='icon-dash text-danger'></i>"; ?>
                                                                </span>
                                                            </a>
                                                        </td>
                                                        <td class="warning" align="center">
                                                            <span class="badge badge-warning ltr">
                                                                <?= convertNumbersToPersian($product['discount']) . ' %' ?: '۰' . ' %'; ?>
                                                            </span>
                                                        </td>
                                                        <td style="width: 115px;" class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="text-danger-600">
                                                                    <a class="deleteFestivalProductBtn"
                                                                       title="حذف" data-popup="tooltip">
                                                                        <input type="hidden"
                                                                               value="<?= $product['f_id']; ?>">
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