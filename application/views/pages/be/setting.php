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
                                    class="text-semibold">تنظیمات سایت</span>
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
                        <li class="active">تنظیمات سایت</li>
                    </ul>
                </div>
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">

                <!-- Centered forms -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">تنظیمات</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="tabbable">
                                <ul class="nav nav-tabs nav-tabs-bottom">
                                    <li class="active">
                                        <a href="#mainPanel" data-toggle="tab">اصلی</a>
                                    </li>
                                    <li>
                                        <a href="#imagesPanel" data-toggle="tab">تنظیمات صفحه اصلی</a>
                                    </li>
                                    <li>
                                        <a href="#footerPanel" data-toggle="tab">تنظیمات فوتر</a>
                                    </li>
                                    <li>
                                        <a href="#adminPanel" data-toggle="tab">تنظیمات پنل ادمین</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content">

                                <!-- ********************* -->
                                <!-- ***** TAB PANEL ***** -->
                                <!-- ********************* -->
                                <div class="tab-pane active" id="mainPanel">
                                    <div class="row no-padding pl-20 pr-20">
                                        <div class="col-md-12">
                                            <!--Error Check-->
                                            <?php if (isset($errors_main) && count($errors_main)): ?>
                                                <div class="alert alert-danger alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <ul class="list-unstyled">
                                                        <?php foreach ($errors_main as $err): ?>
                                                            <li>
                                                                <i class="icon-dash" aria-hidden="true"></i>
                                                                <?= $err; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php elseif (isset($success_main)): ?>
                                                <div class="alert alert-success alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <p>
                                                        <?= $success_main; ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            <!--Error Check End-->
                                        </div>
                                    </div>

                                    <form action="<?= base_url(); ?>admin/setting#mainPanel" method="post">
                                        <?= $data['form_token_main']; ?>

                                        <div class="row p-20 no-padding-top">
                                            <div class="col-lg-6 form-group">
                                                <span class="text-danger">*</span>
                                                <label>آیکون بالای صفحات سایت:</label>
                                                <div class="cursor-pointer pick-file" data-toggle="modal"
                                                     data-target="#modal_full"
                                                     style="border: dashed 2px #ddd; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                    <input class="image-file" type="hidden"
                                                           name="fav"
                                                           value="<?= $setting['main']['favIcon'] ?? ''; ?>">
                                                    <div class="media stack-media-on-mobile">
                                                        <div class="media-left">
                                                            <div class="thumb">
                                                                <a class="display-inline-block"
                                                                   style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                    <img
                                                                            src="<?= set_value($setting['main']['favIcon'] ?? '', '', base_url($setting['main']['favIcon'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                            class="img-rounded" alt=""
                                                                            style="width: 100px; height: 100px; object-fit: contain;"
                                                                            data-base-url="<?= base_url(); ?>">
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="media-body">
                                                            <h6 class="media-heading">
                                                                <a class="text-grey-300">
                                                                    انتخاب تصویر:
                                                                </a>
                                                                <a class="io-image-name display-block">
                                                                    <?= basename($setting['main']['favIcon'] ?? ''); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <span class="text-danger">*</span>
                                                <label>لوگوی سایت:</label>
                                                <div class="cursor-pointer pick-file" data-toggle="modal"
                                                     data-target="#modal_full"
                                                     style="border: dashed 2px #ddd; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                    <input class="image-file" type="hidden"
                                                           name="logo"
                                                           value="<?= $setting['main']['logo'] ?? ''; ?>">
                                                    <div class="media stack-media-on-mobile">
                                                        <div class="media-left">
                                                            <div class="thumb">
                                                                <a class="display-inline-block"
                                                                   style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                    <img
                                                                            src="<?= set_value($setting['main']['logo'] ?? '', '', base_url($setting['main']['logo'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                            class="img-rounded" alt=""
                                                                            style="width: 100px; height: 100px; object-fit: contain;"
                                                                            data-base-url="<?= base_url(); ?>">
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="media-body">
                                                            <h6 class="media-heading">
                                                                <a class="text-grey-300">
                                                                    انتخاب تصویر:
                                                                </a>
                                                                <a class="io-image-name display-block">
                                                                    <?= basename($setting['main']['logo'] ?? ''); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <span class="help-block alert alert-warning no-border-right no-border-top no-border-bottom border-lg pt-5 pb-5">
                                                    یک یا دو کلمه کلیدی و تا حداکثر ۲۰ کاراکتر
                                                </span>
                                                <span class="text-danger">*</span>
                                                <label>عنوان سایت:</label>
                                                <input name="title" type="text"
                                                       class="form-control" placeholder="" maxlength="20"
                                                       value="<?= $setting['main']['title'] ?? ''; ?>">
                                            </div>
                                            <div class="col-md-12">
                                                <div class="alert alert-primary no-border-right no-border-top no-border-bottom border-lg">
                                                    توضیح مختصر و کلمات کلیدی، برای موتورهای جستجوگر است.
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>توضیح مختصر درباره سایت:</label>
                                                <textarea class="form-control col-md-12 p-10"
                                                          style="min-height: 100px; resize: vertical;"
                                                          name="desc"
                                                          rows="4"
                                                          cols="10"><?= $setting['main']['description'] ?? ''; ?></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>کلمات کلیدی:</label>
                                                <input name="keywords" type="text"
                                                       class="form-control" placeholder="Press Enter"
                                                       data-role="tagsinput"
                                                       value="<?= $setting['main']['keywords'] ?? ''; ?>">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <hr style="margin-bottom: 0;">
                                            <button type="submit"
                                                    class="btn btn-default btn-block pt-20 pb-20 no-border-radius-top">
                                                <span class="h5">
                                                <i class="icon-cog position-left"></i>
                                                    ذخیره تنظیمات
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- ********************* -->
                                <!-- ***** TAB PANEL ***** -->
                                <!-- ********************* -->
                                <div class="tab-pane" id="imagesPanel">
                                    <div class="row no-padding pl-20 pr-20">
                                        <div class="col-md-12">
                                            <!--Error Check-->
                                            <?php if (isset($errors_images) && count($errors_images)): ?>
                                                <div class="alert alert-danger alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <ul class="list-unstyled">
                                                        <?php foreach ($errors_images as $err): ?>
                                                            <li>
                                                                <i class="icon-dash" aria-hidden="true"></i>
                                                                <?= $err; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php elseif (isset($success_images)): ?>
                                                <div class="alert alert-success alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <p>
                                                        <?= $success_images; ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            <!--Error Check End-->
                                        </div>
                                    </div>

                                    <form action="<?= base_url(); ?>admin/setting#imagesPanel" method="post">
                                        <?= $data['form_token_images']; ?>

                                        <div class="row pl-20 pr-20">
                                            <div class="col-lg-12">
                                                <label class="m-0 pt-5 pb-5 pl-10 pr-10 display-block bg-white btn-default border-left
                                                border-left-info border-left-xlg shadow-depth1 btn-rounded text-right"
                                                       for="showBrandStatus">
                                                    <span class="pull-left h5 no-margin">
                                                        <i class="icon-switch2 position-left text-info"></i>
                                                        نمایش اسلایدر برندها
                                                    </span>
                                                    <input type="checkbox" name="showBrands" id="showBrandStatus"
                                                           class="switchery" <?= set_value($setting['pages']['index']['showBrands'] ?? '', 1, 'checked', '', '=='); ?> />
                                                </label>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                تصویر بالای اسلایدر اصلی
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="form-group col-md-9">
                                                <label>لینک:</label>
                                                <input name="imgTopLink" type="text" class="form-control"
                                                       placeholder="لینک"
                                                       value="<?= set_value($setting['pages']['index']['topImage']['link'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-12 mt-10 mb-10">
                                                <div class="cursor-pointer pick-file" data-toggle="modal"
                                                     data-target="#modal_full"
                                                     style="border: dashed 2px #ddd; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                    <input class="image-file" type="hidden"
                                                           name="imgTop"
                                                           value="<?= $setting['pages']['index']['topImage']['image'] ?? ''; ?>">
                                                    <div class="media stack-media-on-mobile">
                                                        <div class="media-left">
                                                            <div class="thumb">
                                                                <a class="display-inline-block"
                                                                   style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                    <img
                                                                            src="<?= set_value($setting['pages']['index']['topImage']['image'] ?? '', '', base_url($setting['pages']['index']['topImage']['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                            class="img-rounded" alt=""
                                                                            style="width: 100px; height: 100px; object-fit: contain;"
                                                                            data-base-url="<?= base_url(); ?>">
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="media-body">
                                                            <h6 class="media-heading">
                                                                <a class="text-grey-300">
                                                                    انتخاب تصویر:
                                                                </a>
                                                                <a class="io-image-name display-block">
                                                                    <?= basename($setting['pages']['index']['topImage']['image'] ?? ''); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                        <small class="clear-img-val">&times;</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                تصویر کنار اسلایدر اصلی
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="form-group col-md-9">
                                                <label>لینک:</label>
                                                <input name="imgNextLink" type="text" class="form-control"
                                                       placeholder="لینک"
                                                       value="<?= set_value($setting['pages']['index']['nextToSliderImage']['link'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-12 mt-10 mb-10">
                                                <div class="cursor-pointer pick-file" data-toggle="modal"
                                                     data-target="#modal_full"
                                                     style="border: dashed 2px #ddd; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                    <input class="image-file" type="hidden"
                                                           name="imgNext"
                                                           value="<?= $setting['pages']['index']['nextToSliderImage']['image'] ?? ''; ?>">
                                                    <div class="media stack-media-on-mobile">
                                                        <div class="media-left">
                                                            <div class="thumb">
                                                                <a class="display-inline-block"
                                                                   style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                    <img
                                                                            src="<?= set_value($setting['pages']['index']['nextToSliderImage']['image'] ?? '', '', base_url($setting['pages']['index']['nextToSliderImage']['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                            class="img-rounded" alt=""
                                                                            style="width: 100px; height: 100px; object-fit: contain;"
                                                                            data-base-url="<?= base_url(); ?>">
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="media-body">
                                                            <h6 class="media-heading">
                                                                <a class="text-grey-300">
                                                                    انتخاب تصویر:
                                                                </a>
                                                                <a class="io-image-name display-block">
                                                                    <?= basename($setting['pages']['index']['nextToSliderImage']['image'] ?? ''); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                        <small class="clear-img-val">&times;</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin text-left">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                تصاویر کنار صفحه
                                                <a href="javascript:void(0);"
                                                   class="btn btn-info text-black btn-rounded add-slide-image ml-5 pull-right"
                                                   title="اضافه کردن تصویر جدید" data-popup="tooltip">
                                                    <i class="icon-plus2" aria-hidden="true"></i>
                                                    افزودن
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20 slide-items">
                                            <?php
                                            if ((isset($setting['pages']['index']['sideImages']['images']) && count($setting['pages']['index']['sideImages']['images'])) ||
                                                (isset($setting['pages']['index']['sideImages']['links']) && count($setting['pages']['index']['sideImages']['links']))):
                                                ?>
                                                <?php foreach ($setting['pages']['index']['sideImages']['images'] as $key => $img): ?>
                                                <div class="col-md-6 col-sm-12 mb-15 slide-item">
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-sm-9">
                                                            <label>لینک:</label>
                                                            <input name="imgSideLink[]" type="text" class="form-control"
                                                                   placeholder="لینک"
                                                                   value="<?= set_value($setting['pages']['index']['sideImages']['links'][$key] ?? ''); ?>">
                                                        </div>
                                                        <div class="col-md-12 col-sm-12">
                                                            <div class="cursor-pointer pick-file border border-lg border-default"
                                                                 data-toggle="modal"
                                                                 data-target="#modal_full"
                                                                 style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                                <input class="image-file" type="hidden"
                                                                       name="imgSide[]"
                                                                       value="<?= set_value($img ?? ''); ?>">
                                                                <div class="media stack-media-on-mobile">
                                                                    <div class="media-left">
                                                                        <div class="thumb">
                                                                            <a class="display-inline-block"
                                                                               style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                                <img
                                                                                        src="<?= set_value($img ?? '', '', base_url($img ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                                        class="img-rounded" alt=""
                                                                                        style="width: 100px; height: 100px; object-fit: contain;"
                                                                                        data-base-url="<?= base_url(); ?>">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h6 class="media-heading">
                                                                            <a class="io-image-lbl text-grey-300">
                                                                                انتخاب تصویر<?= ($key + 1); ?>
                                                                            </a>
                                                                            <a class="io-image-name display-block">
                                                                                <?= basename(set_value($img ?? '')); ?>
                                                                            </a>
                                                                        </h6>
                                                                    </div>
                                                                    <?php if ($key == 0): ?>
                                                                        <small class="clear-img-val">&times;</small>
                                                                    <?php else: ?>
                                                                        <small class="delete-new-image btn btn-danger"
                                                                               title="حذف">×
                                                                        </small>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="col-md-6 col-sm-12 mb-15 slide-item">
                                                    <div class="row">
                                                        <div class="form-group col-md-12 col-sm-9">
                                                            <label>لینک:</label>
                                                            <input name="imgSideLink[]" type="text" class="form-control"
                                                                   placeholder="لینک">
                                                        </div>
                                                        <div class="col-md-12 col-sm-12">
                                                            <div class="cursor-pointer pick-file border border-lg border-default"
                                                                 data-toggle="modal"
                                                                 data-target="#modal_full"
                                                                 style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                                <input class="image-file" type="hidden"
                                                                       name="imgSide[]">
                                                                <div class="media stack-media-on-mobile">
                                                                    <div class="media-left">
                                                                        <div class="thumb">
                                                                            <a class="display-inline-block"
                                                                               style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                                <img
                                                                                        src="<?= asset_url('be/images/placeholder.jpg'); ?>"
                                                                                        class="img-rounded" alt=""
                                                                                        style="width: 100px; height: 100px; object-fit: contain;"
                                                                                        data-base-url="<?= base_url(); ?>">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h6 class="media-heading">
                                                                            <a class="io-image-lbl text-grey-300">
                                                                                انتخاب تصویر 1
                                                                            </a>
                                                                            <a class="io-image-name display-block">
                                                                            </a>
                                                                        </h6>
                                                                    </div>
                                                                    <small class="clear-img-val">&times;</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                آیتم‌های بعد از اسلایدر اصلی
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <?php
                                            $emptyArr4 = array_fill(0, 4, '');
                                            $emptyArr2 = array_fill(0, 2, '');
                                            ?>
                                            <div class="col-xs-12 mb-20">
                                                <span class="h4 pb-5">
                                                    تصاویر چهارتایی
                                                    <i class="icon-arrow-left8 position-right text-pink"></i>
                                                </span>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <?php foreach ($setting['pages']['index']['fourImages']['images'] ?? $emptyArr4 as $key => $img): ?>
                                                        <div class="col-md-6 mb-15">
                                                            <div class="row">
                                                                <div class="form-group col-md-12 col-sm-9">
                                                                    <label>لینک:</label>
                                                                    <input name="imgFourImagesLink[]" type="text"
                                                                           class="form-control"
                                                                           placeholder="لینک"
                                                                           value="<?= set_value($setting['pages']['index']['fourImages']['links'][$key] ?? ''); ?>">
                                                                </div>
                                                                <div class="col-md-12 col-sm-12">
                                                                    <div class="cursor-pointer pick-file border border-lg border-default"
                                                                         data-toggle="modal"
                                                                         data-target="#modal_full"
                                                                         style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                                        <input class="image-file" type="hidden"
                                                                               name="imgFourImages[]"
                                                                               value="<?= set_value($img ?? ''); ?>">
                                                                        <div class="media stack-media-on-mobile">
                                                                            <div class="media-left">
                                                                                <div class="thumb">
                                                                                    <a class="display-inline-block"
                                                                                       style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                                        <img
                                                                                                src="<?= set_value($img ?? '', '', base_url($img ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                                                class="img-rounded"
                                                                                                alt=""
                                                                                                style="width: 100px; height: 100px; object-fit: contain;"
                                                                                                data-base-url="<?= base_url(); ?>">
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="media-body">
                                                                                <h6 class="media-heading">
                                                                                    <a class="io-image-lbl text-grey-300">
                                                                                        انتخاب تصویر
                                                                                    </a>
                                                                                    <a class="io-image-name display-block">
                                                                                        <?= basename(set_value($img ?? '')); ?>
                                                                                    </a>
                                                                                </h6>
                                                                                <small class="clear-img-val">&times;
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="col-xs-12" style="margin-right: -10px;">

                                            <div class="col-xs-12 mb-20">
                                                <span class="h4 pb-5">
                                                    تصاویر دوتایی
                                                    <i class="icon-arrow-left8 position-right text-pink"></i>
                                                </span>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <?php foreach ($setting['pages']['index']['twoImages']['images'] ?? $emptyArr2 as $key => $img): ?>
                                                        <div class="col-md-6 mb-15">
                                                            <div class="row">
                                                                <div class="form-group col-md-12 col-sm-9">
                                                                    <label>لینک:</label>
                                                                    <input name="imgTwoImagesLink[]" type="text"
                                                                           class="form-control"
                                                                           placeholder="لینک"
                                                                           value="<?= set_value($setting['pages']['index']['twoImages']['links'][$key] ?? ''); ?>">
                                                                </div>
                                                                <div class="col-md-12 col-sm-12">
                                                                    <div class="cursor-pointer pick-file border border-lg border-default"
                                                                         data-toggle="modal"
                                                                         data-target="#modal_full"
                                                                         style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                                        <input class="image-file" type="hidden"
                                                                               name="imgTwoImages[]"
                                                                               value="<?= set_value($img ?? ''); ?>">
                                                                        <div class="media stack-media-on-mobile">
                                                                            <div class="media-left">
                                                                                <div class="thumb">
                                                                                    <a class="display-inline-block"
                                                                                       style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                                        <img
                                                                                                src="<?= set_value($img ?? '', '', base_url($img ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                                                class="img-rounded"
                                                                                                alt=""
                                                                                                style="width: 100px; height: 100px; object-fit: contain;"
                                                                                                data-base-url="<?= base_url(); ?>">
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="media-body">
                                                                                <h6 class="media-heading">
                                                                                    <a class="io-image-lbl text-grey-300">
                                                                                        انتخاب تصویر
                                                                                    </a>
                                                                                    <a class="io-image-name display-block">
                                                                                        <?= basename(set_value($img ?? '')); ?>
                                                                                    </a>
                                                                                </h6>
                                                                                <small class="clear-img-val">&times;
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <hr class="col-xs-12" style="margin-right: -10px;">

                                            <div class="col-xs-12 mb-20">
                                                <span class="h4 pb-5">
                                                    تصویر تکی
                                                    <i class="icon-arrow-left8 position-right text-pink"></i>
                                                </span>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="form-group col-sm-9">
                                                        <label>لینک:</label>
                                                        <input name="imgOneImageLink" type="text" class="form-control"
                                                               placeholder="لینک"
                                                               value="<?= set_value($setting['pages']['index']['oneImage']['link'] ?? ''); ?>">
                                                    </div>
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="cursor-pointer pick-file border border-lg border-default"
                                                             data-toggle="modal"
                                                             data-target="#modal_full"
                                                             style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                            <input class="image-file" type="hidden"
                                                                   name="imgOneImage"
                                                                   value="<?= set_value($setting['pages']['index']['oneImage']['image'] ?? ''); ?>">
                                                            <div class="media stack-media-on-mobile">
                                                                <div class="media-left">
                                                                    <div class="thumb">
                                                                        <a class="display-inline-block"
                                                                           style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                            <img
                                                                                    src="<?= set_value($setting['pages']['index']['oneImage']['image'] ?? '', '', base_url($setting['pages']['index']['oneImage']['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                                    class="img-rounded" alt=""
                                                                                    style="width: 100px; height: 100px; object-fit: contain;"
                                                                                    data-base-url="<?= base_url(); ?>">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="media-body">
                                                                    <h6 class="media-heading">
                                                                        <a class="io-image-lbl text-grey-300">
                                                                            انتخاب تصویر
                                                                        </a>
                                                                        <a class="io-image-name display-block">
                                                                            <?= basename(set_value($setting['pages']['index']['oneImage']['image'] ?? '')); ?>
                                                                        </a>
                                                                    </h6>
                                                                </div>
                                                                <small class="clear-img-val">&times;</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="col-xs-12" style="margin-right: -10px;">

                                            <div class="col-xs-12 mb-20">
                                                <span class="h4 pb-5">
                                                    اسلایدر‌ها
                                                    <i class="icon-arrow-left8 position-right text-pink"></i>
                                                </span>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 mb-20">
                                                        <span class="h6 pb-5 text-muted">
                                                            <i class="icon-dash"></i>
                                                            اسلایدر اول
                                                        </span>
                                                    </div>
                                                    <div class="form-group col-md-5 col-sm-12">
                                                        <label>عنوان اسلایدر:</label>
                                                        <input name="sliderTitle[]" type="text"
                                                               class="form-control"
                                                               placeholder="عنوان"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['title'][0] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-7 col-sm-12">
                                                        <label>لینک مشاهده همه:</label>
                                                        <input name="sliderViewAll[]" type="text"
                                                               class="form-control"
                                                               placeholder="لینک"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['viewAllLink'][0] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-8 col-sm-12">
                                                        <label>نمایش از دسته‌بندی:</label>
                                                        <select class="select-rtl" name="sliderCategory[]">
                                                            <option value="0" selected disabled>انتخاب کنید</option>
                                                            <?php foreach ($categories as $key => $category): ?>
                                                                <option value="<?= $category['id']; ?>"
                                                                    <?= set_value($setting['pages']['index']['sliders']['category'][0] ?? '', $category['id'], 'selected', '', '=='); ?>>
                                                                    <?php for ($i = 1; $i < $category['level']; $i++): ?>
                                                                        -
                                                                    <?php endfor; ?>
                                                                    <?= $category['category_name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4 col-sm-12">
                                                        <label>تعداد محصولات:</label>
                                                        <input name="sliderItemsCount[]" type="text"
                                                               class="form-control"
                                                               placeholder="عددی بین ۱۰ و ۳۰"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['count'][0] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-xs-push-4 mt-20 mb-20 border-top border-top-dashed border-grey-300"></div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 mb-20">
                                                        <span class="h6 pb-5 text-muted">
                                                            <i class="icon-dash"></i>
                                                            اسلایدر دوم
                                                        </span>
                                                    </div>
                                                    <div class="form-group col-md-5 col-sm-12">
                                                        <label>عنوان اسلایدر:</label>
                                                        <input name="sliderTitle[]" type="text"
                                                               class="form-control"
                                                               placeholder="عنوان"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['title'][1] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-7 col-sm-12">
                                                        <label>لینک مشاهده همه:</label>
                                                        <input name="sliderViewAll[]" type="text"
                                                               class="form-control"
                                                               placeholder="لینک"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['viewAllLink'][1] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-8 col-sm-12">
                                                        <label>نمایش از دسته‌بندی:</label>
                                                        <select class="select-rtl" name="sliderCategory[]">
                                                            <option value="0" selected disabled>انتخاب کنید</option>
                                                            <?php foreach ($categories as $key => $category): ?>
                                                                <option value="<?= $category['id']; ?>"
                                                                    <?= set_value($setting['pages']['index']['sliders']['category'][1] ?? '', $category['id'], 'selected', '', '=='); ?>>
                                                                    <?php for ($i = 1; $i < $category['level']; $i++): ?>
                                                                        -
                                                                    <?php endfor; ?>
                                                                    <?= $category['category_name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4 col-sm-12">
                                                        <label>تعداد محصولات:</label>
                                                        <input name="sliderItemsCount[]" type="text"
                                                               class="form-control"
                                                               placeholder="عددی بین ۱۰ و ۳۰"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['count'][1] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-xs-push-4 mt-20 mb-20 border-top border-top-dashed border-grey-300"></div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 mb-20">
                                                        <span class="h6 pb-5 text-muted">
                                                            <i class="icon-dash"></i>
                                                            اسلایدر سوم
                                                        </span>
                                                    </div>
                                                    <div class="form-group col-md-5 col-sm-12">
                                                        <label>عنوان اسلایدر:</label>
                                                        <input name="sliderTitle[]" type="text"
                                                               class="form-control"
                                                               placeholder="عنوان"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['title'][2] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-7 col-sm-12">
                                                        <label>لینک مشاهده همه:</label>
                                                        <input name="sliderViewAll[]" type="text"
                                                               class="form-control"
                                                               placeholder="لینک"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['viewAllLink'][2] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-8 col-sm-12">
                                                        <label>نمایش از دسته‌بندی:</label>
                                                        <select class="select-rtl" name="sliderCategory[]">
                                                            <option value="0" selected disabled>انتخاب کنید</option>
                                                            <?php foreach ($categories as $key => $category): ?>
                                                                <option value="<?= $category['id']; ?>"
                                                                    <?= set_value($setting['pages']['index']['sliders']['category'][2] ?? '', $category['id'], 'selected', '', '=='); ?>>
                                                                    <?php for ($i = 1; $i < $category['level']; $i++): ?>
                                                                        -
                                                                    <?php endfor; ?>
                                                                    <?= $category['category_name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4 col-sm-12">
                                                        <label>تعداد محصولات:</label>
                                                        <input name="sliderItemsCount[]" type="text"
                                                               class="form-control"
                                                               placeholder="عددی بین ۱۰ و ۳۰"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['count'][2] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-xs-push-4 mt-20 mb-20 border-top border-top-dashed border-grey-300"></div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 mb-20">
                                                        <span class="h6 pb-5 text-muted">
                                                            <i class="icon-dash"></i>
                                                            اسلایدر چهارم
                                                        </span>
                                                    </div>
                                                    <div class="form-group col-md-5 col-sm-12">
                                                        <label>عنوان اسلایدر:</label>
                                                        <input name="sliderTitle[]" type="text"
                                                               class="form-control"
                                                               placeholder="عنوان"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['title'][3] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-7 col-sm-12">
                                                        <label>لینک مشاهده همه:</label>
                                                        <input name="sliderViewAll[]" type="text"
                                                               class="form-control"
                                                               placeholder="لینک"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['viewAllLink'][3] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-8 col-sm-12">
                                                        <label>نمایش از دسته‌بندی:</label>
                                                        <select class="select-rtl" name="sliderCategory[]">
                                                            <option value="0" selected disabled>انتخاب کنید</option>
                                                            <?php foreach ($categories as $key => $category): ?>
                                                                <option value="<?= $category['id']; ?>"
                                                                    <?= set_value($setting['pages']['index']['sliders']['category'][3] ?? '', $category['id'], 'selected', '', '=='); ?>>
                                                                    <?php for ($i = 1; $i < $category['level']; $i++): ?>
                                                                        -
                                                                    <?php endfor; ?>
                                                                    <?= $category['category_name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4 col-sm-12">
                                                        <label>تعداد محصولات:</label>
                                                        <input name="sliderItemsCount[]" type="text"
                                                               class="form-control"
                                                               placeholder="عددی بین ۱۰ و ۳۰"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['count'][3] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-xs-push-4 mt-20 mb-20 border-top border-top-dashed border-grey-300"></div>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 mb-20">
                                                        <span class="h6 pb-5 text-muted">
                                                            <i class="icon-dash"></i>
                                                            اسلایدر پنجم
                                                        </span>
                                                    </div>
                                                    <div class="form-group col-md-5 col-sm-12">
                                                        <label>عنوان اسلایدر:</label>
                                                        <input name="sliderTitle[]" type="text"
                                                               class="form-control"
                                                               placeholder="عنوان"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['title'][4] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-7 col-sm-12">
                                                        <label>لینک مشاهده همه:</label>
                                                        <input name="sliderViewAll[]" type="text"
                                                               class="form-control"
                                                               placeholder="لینک"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['viewAllLink'][4] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-8 col-sm-12">
                                                        <label>نمایش از دسته‌بندی:</label>
                                                        <select class="select-rtl" name="sliderCategory[]">
                                                            <option value="0" selected disabled>انتخاب کنید</option>
                                                            <?php foreach ($categories as $key => $category): ?>
                                                                <option value="<?= $category['id']; ?>"
                                                                    <?= set_value($setting['pages']['index']['sliders']['category'][4] ?? '', $category['id'], 'selected', '', '=='); ?>>
                                                                    <?php for ($i = 1; $i < $category['level']; $i++): ?>
                                                                        -
                                                                    <?php endfor; ?>
                                                                    <?= $category['category_name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4 col-sm-12">
                                                        <label>تعداد محصولات:</label>
                                                        <input name="sliderItemsCount[]" type="text"
                                                               class="form-control"
                                                               placeholder="عددی بین ۱۰ و ۳۰"
                                                               value="<?= set_value($setting['pages']['index']['sliders']['count'][4] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <hr style="margin-bottom: 0;">
                                            <button type="submit"
                                                    class="btn btn-default btn-block pt-20 pb-20 no-border-radius-top">
                                                <span class="h5">
                                                <i class="icon-cog position-left"></i>
                                                    ذخیره تنظیمات
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- ********************* -->
                                <!-- ***** TAB PANEL ***** -->
                                <!-- ********************* -->
                                <div class="tab-pane" id="footerPanel">
                                    <div class="row no-padding pl-20 pr-20">
                                        <div class="col-md-12">
                                            <!--Error Check-->
                                            <?php if (isset($errors_footer) && count($errors_footer)): ?>
                                                <div class="alert alert-danger alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <ul class="list-unstyled">
                                                        <?php foreach ($errors_footer as $err): ?>
                                                            <li>
                                                                <i class="icon-dash" aria-hidden="true"></i>
                                                                <?= $err; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php elseif (isset($success_footer)): ?>
                                                <div class="alert alert-success alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <p>
                                                        <?= $success_footer; ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            <!--Error Check End-->
                                        </div>
                                    </div>

                                    <form action="<?= base_url(); ?>admin/setting#footerPanel" method="post">
                                        <?= $data['form_token_footer']; ?>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                مدیریت لینک‌ها
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="col-lg-4 mb-10">
                                                <div class="border border-dashed border-grey-300 border-radius p-10">
                                                    <div class="form-group col-md-12">
                                                        <label>عنوان بخش اول:</label>
                                                        <input name="footer_1_title[]" type="text" class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['title'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][0]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][0]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][1]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][1]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][2]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][2]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][3]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][3]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][4]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_1']['links'][4]['link'] ?? ''); ?>">
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-10">
                                                <div class="border border-dashed border-grey-300 border-radius p-10">
                                                    <div class="form-group col-md-12">
                                                        <label>عنوان بخش دوم:</label>
                                                        <input name="footer_1_title[]" type="text" class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['title'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][0]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][0]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][1]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][1]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][2]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][2]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][3]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][3]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][4]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_2']['links'][4]['link'] ?? ''); ?>">
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-10">
                                                <div class="border border-dashed border-grey-300 border-radius p-10">
                                                    <div class="form-group col-md-12">
                                                        <label>عنوان بخش سوم:</label>
                                                        <input name="footer_1_title[]" type="text" class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['title'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][0]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][0]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][1]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][1]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][2]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][2]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][3]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][3]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][4]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['footer_1']['sections']['section_3']['links'][4]['link'] ?? ''); ?>">
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                شبکه‌های اجتماعی
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="col-lg-6 mt-10">
                                                <div class="form-group">
                                                    <label>آدرس اینستاگرام:</label>
                                                    <input name="instagram" type="text"
                                                           class="form-control" placeholder=""
                                                           value="<?= set_value($setting['footer']['footer_1']['socials']['instagram'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                اطلاعات تماس
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="col-lg-12 mb-10">
                                                <div class="form-group col-lg-12">
                                                    <label>اطلاعات شماره ۱:</label>
                                                    <textarea class="form-control col-md-12 p-10"
                                                              style="min-height: 100px; resize: vertical;"
                                                              name="information_1"
                                                              rows="4"
                                                              cols="10"><?= set_value($setting['footer']['footer_2']['section_1'] ?? ''); ?></textarea>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>اطلاعات شماره ۲:</label>
                                                    <input name="information_2" type="text" class="form-control"
                                                           placeholder=""
                                                           value="<?= set_value($setting['footer']['footer_2']['section_2'] ?? ''); ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>اطلاعات شماره ۳:</label>
                                                    <input name="information_3" type="text" class="form-control"
                                                           placeholder=""
                                                           value="<?= set_value($setting['footer']['footer_2']['section_3'] ?? ''); ?>">
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                درباره فروشگاه
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="col-lg-12 mb-10">
                                                <div class="form-group col-md-8">
                                                    <label>عنوان:</label>
                                                    <input name="descTitle" type="text" class="form-control"
                                                           placeholder=""
                                                           value="<?= set_value($setting['footer']['footer_3']['description']['title'] ?? ''); ?>">
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label>توضیحات:</label>
                                                    <textarea class="form-control col-md-12 p-10"
                                                              style="min-height: 100px; resize: vertical;"
                                                              name="desc"
                                                              rows="4"
                                                              cols="10"><?= set_value($setting['footer']['footer_3']['description']['description'] ?? ''); ?></textarea>
                                                </div>
                                                <div class="col-lg-12 border border-dashed border-grey-300 border-radius p-20">
                                                    <div class="form-group col-md-6">
                                                        <label>مجوز فروشگاه شماره ۱:</label>
                                                        <textarea class="form-control col-md-12 p-10 ltr"
                                                                  style="min-height: 100px; resize: vertical;"
                                                                  name="namad_1" rows="4"
                                                                  placeholder="لینک مربوط به نماد"
                                                                  cols="10"><?= set_value($setting['footer']['footer_3']['namad']['namad_1'] ?? ''); ?></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>مجوز فروشگاه شماره ۲:</label>
                                                        <textarea class="form-control col-md-12 p-10 ltr"
                                                                  style="min-height: 100px; resize: vertical;"
                                                                  name="namad_2" rows="4"
                                                                  placeholder="لینک مربوط به نماد"
                                                                  cols="10"><?= set_value($setting['footer']['footer_3']['namad']['namad_2'] ?? ''); ?></textarea>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <hr style="margin-bottom: 0;">
                                            <button type="submit"
                                                    class="btn btn-default btn-block pt-20 pb-20 no-border-radius-top">
                                                <span class="h5">
                                                <i class="icon-cog position-left"></i>
                                                    ذخیره تنظیمات
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- ********************* -->
                                <!-- ***** TAB PANEL ***** -->
                                <!-- ********************* -->
                                <div class="tab-pane" id="adminPanel">
                                    <div class="row no-padding pl-20 pr-20">
                                        <div class="col-md-12">
                                            <!--Error Check-->
                                            <?php if (isset($errors_admin) && count($errors_admin)): ?>
                                                <div class="alert alert-danger alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <ul class="list-unstyled">
                                                        <?php foreach ($errors_admin as $err): ?>
                                                            <li>
                                                                <i class="icon-dash" aria-hidden="true"></i>
                                                                <?= $err; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php elseif (isset($success_admin)): ?>
                                                <div class="alert alert-success alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <p>
                                                        <?= $success_admin; ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            <!--Error Check End-->
                                        </div>
                                    </div>

                                    <form action="<?= base_url(); ?>admin/setting#adminPanel" method="post">
                                        <?= $data['form_token_admin']; ?>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                نوع عملکرد اعتبارسنجی در افزودن یک محصول در دو پیشنهاد
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="col-lg-12">
                                                <div class="p-10">
                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-check-right">
                                                            <label class="form-check-label ltr">
                                                                <span class="rtl">
                                                                    نمایش خطا و عدم ذخیره‌سازی محصول
                                                                </span>
                                                                <input type="radio" class="control-custom"
                                                                       name="offerReaction" value="1"
                                                                    <?= set_value($setting['admin']['panel']['offer_reaction'] ?? '', '1', 'checked', '', '=='); ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-check-right">
                                                            <label class="form-check-label ltr">
                                                                <span class="rtl">
                                                                    نمایش پیغام و ذخیره‌سازی محصول و غیرفعالسازی پیشنهاد دارای محصول مورد نظر
                                                                </span>
                                                                <input type="radio" class="control-custom"
                                                                       name="offerReaction" value="2"
                                                                    <?= set_value($setting['admin']['panel']['offer_reaction'] ?? '', '2', 'checked', '', '=='); ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                نوع عملکرد اعتبارسنجی در فعالسازی یک پیشنهاد
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="col-lg-12">
                                                <div class="p-10">
                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-check-right">
                                                            <label class="form-check-label ltr">
                                                                <span class="rtl">
                                                                    نمایش خطا و عدم فعالسازی پیشنهاد
                                                                </span>
                                                                <input type="radio" class="control-custom"
                                                                       name="offerActivationReaction" value="1"
                                                                    <?= set_value($setting['admin']['panel']['offer_activation_reaction'] ?? '', '1', 'checked', '', '=='); ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-check-right">
                                                            <label class="form-check-label ltr">
                                                                <span class="rtl">
                                                                    نمایش پیغام و فعالسازی پیشنهاد و غیرفعالسازی پیشنهادهای فعال دارای محصول مشترک
                                                                </span>
                                                                <input type="radio" class="control-custom"
                                                                       name="offerActivationReaction" value="2"
                                                                    <?= set_value($setting['admin']['panel']['offer_activation_reaction'] ?? '', '2', 'checked', '', '=='); ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <div class="form-check form-check-right">
                                                            <label class="form-check-label ltr">
                                                                <span class="rtl">
                                                                    نمایش پیغام و حذف محصول مشترک از سایر پیشنهادهای فعال
                                                                </span>
                                                                <input type="radio" class="control-custom"
                                                                       name="offerActivationReaction" value="3"
                                                                    <?= set_value($setting['admin']['panel']['offer_activation_reaction'] ?? '', '3', 'checked', '', '=='); ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <hr style="margin-bottom: 0;">
                                            <button type="submit"
                                                    class="btn btn-default btn-block pt-20 pb-20 no-border-radius-top">
                                                <span class="h5">
                                                <i class="icon-cog position-left"></i>
                                                    ذخیره تنظیمات
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /form centered -->

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

                    <!-- Show active tab from url's hash -->
                    <script>
                        var hash = window.location.hash.substr(1);
                        var tabs = ['mainPanel', 'imagesPanel', 'footerPanel', 'adminPanel'];

                        if ($.inArray(hash, tabs) !== -1) {
                            $('a[href="#' + hash + '"]').tab('show');
                        }
                    </script>
                    <!-- /Show active tab from url's hash -->

                    <!-- Footer -->
                    <?php $this->view("templates/be/copyright", $data); ?>
                    <!-- /footer -->
                </div>
            </div>
            <!-- /content area -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->