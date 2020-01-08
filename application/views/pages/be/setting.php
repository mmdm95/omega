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

                                        <div class="row">
                                            <div class="pl-20 pr-20">
                                                <div class="col-lg-6 form-group">
                                                    <div class="mb-20">
                                                        <span class="h4 pb-5">
                                                            <i class="position-left text-pink">*</i>
                                                            آیکون بالای صفحات سایت
                                                        </span>
                                                    </div>
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
                                            </div>

                                            <div class="pl-20 pr-20">
                                                <div class="col-lg-6 form-group">
                                                    <div class="mb-20">
                                                        <span class="h4 pb-5">
                                                            <i class="position-left text-pink">*</i>
                                                            لوگوی سایت
                                                        </span>
                                                    </div>
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
                                            </div>

                                            <div class="col-lg-12">
                                                <hr>
                                            </div>

                                            <div class="pl-20 pr-20">
                                                <div class="form-group col-lg-12">
                                                    <div class="mb-20">
                                                        <span class="h4 pb-5">
                                                            <i class="position-left text-pink">*</i>
                                                            عنوان سایت
                                                        </span>
                                                    </div>
                                                    <input name="title" type="text"
                                                           class="form-control" placeholder="" maxlength="20"
                                                           value="<?= $setting['main']['title'] ?? ''; ?>">
                                                    <span class="help-block alert alert-warning no-border-right no-border-top no-border-bottom border-lg pt-5 pb-5">
                                                        یک یا دو کلمه کلیدی و تا حداکثر ۲۰ کاراکتر
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <hr>
                                            </div>

                                            <div class="pl-20 pr-20">
                                                <div class="col-md-12">
                                                    <div class="mb-20">
                                                        <span class="h4 pb-5">
                                                            <i class="icon-dash text-pink"></i>
                                                            توضیح مختصر درباره سایت
                                                        </span>
                                                    </div>
                                                    <div class="alert alert-primary no-border-right no-border-top no-border-bottom border-lg">
                                                        توضیح مختصر و کلمات کلیدی، برای موتورهای جستجوگر است.
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
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

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                تصویر بالای صفحه
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-20 mb-20 border-bottom border-top border-default bg-default">
                                            <h4 class="no-margin">
                                                <i class="icon-circle-small position-left text-info"></i>
                                                قسمت ویژگی‌ها
                                            </h4>
                                        </div>
                                        <div class="row pl-20 pr-20 pb-20">
                                            <div class="col-md-12 mt-10 mb-10">
                                                <div class="cursor-pointer pick-file" data-toggle="modal"
                                                     data-target="#modal_full"
                                                     style="border: dashed 2px #ddd; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                    <input class="image-file" type="hidden"
                                                           name="imgMiddle"
                                                           value="<?= $setting['pages']['index']['middlePart']['image'] ?? ''; ?>">
                                                    <div class="media stack-media-on-mobile">
                                                        <div class="media-left">
                                                            <div class="thumb">
                                                                <a class="display-inline-block"
                                                                   style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                    <img
                                                                            src="<?= set_value($setting['pages']['index']['middlePart']['image'] ?? '', '', base_url($setting['pages']['index']['middlePart']['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
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
                                                                    <?= basename($setting['pages']['index']['middlePart']['image'] ?? ''); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border border-dashed border-grey-300 border-radius p-10 mt-10">
                                                    <div class="form-group">
                                                        <label>عنوان ویژگی:</label>
                                                        <input name="middleTitle[]" type="text" class="form-control"
                                                               placeholder="عنوان"
                                                               value="<?= set_value($setting['pages']['index']['middlePart']['titles'][0] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>توضیح ویژگی:</label>
                                                        <input name="middleDesc[]" type="text" class="form-control"
                                                               placeholder="توضیح"
                                                               value="<?= set_value($setting['pages']['index']['middlePart']['description'][0] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border border-dashed border-grey-300 border-radius p-10 mt-10">
                                                    <div class="form-group">
                                                        <label>عنوان ویژگی:</label>
                                                        <input name="middleTitle[]" type="text" class="form-control"
                                                               placeholder="عنوان"
                                                               value="<?= set_value($setting['pages']['index']['middlePart']['titles'][1] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>توضیح ویژگی:</label>
                                                        <input name="middleDesc[]" type="text" class="form-control"
                                                               placeholder="توضیح"
                                                               value="<?= set_value($setting['pages']['index']['middlePart']['description'][1] ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border border-dashed border-grey-300 border-radius p-10 mt-10">
                                                    <div class="form-group">
                                                        <label>عنوان ویژگی:</label>
                                                        <input name="middleTitle[]" type="text" class="form-control"
                                                               placeholder="عنوان"
                                                               value="<?= set_value($setting['pages']['index']['middlePart']['titles'][2] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>توضیح ویژگی:</label>
                                                        <input name="middleDesc[]" type="text" class="form-control"
                                                               placeholder="توضیح"
                                                               value="<?= set_value($setting['pages']['index']['middlePart']['description'][2] ?? ''); ?>">
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
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['title'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][0]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][0]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][1]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][1]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][2]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][2]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][3]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][3]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][4]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][4]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][5]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[0][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_1']['links'][5]['link'] ?? ''); ?>">
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
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['title'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][0]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][0]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][1]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][1]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][2]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][2]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][3]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][3]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][4]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][4]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][5]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[1][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_2']['links'][5]['link'] ?? ''); ?>">
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
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['title'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][0]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][0]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][1]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][1]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][2]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][2]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][3]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][3]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][4]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][4]['link'] ?? ''); ?>">
                                                    </div>

                                                    <div class="col-md-8 col-md-push-2 border-top border-top-dashed border-grey-300 mt-10 mb-20"></div>

                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>متن لینک:</label>
                                                        <input name="footer_1_text[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="متن"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][5]['text'] ?? ''); ?>">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-12">
                                                        <label>آدرس لینک:</label>
                                                        <input name="footer_1_link[2][]" type="text"
                                                               class="form-control"
                                                               placeholder="آدرس"
                                                               value="<?= set_value($setting['footer']['sections']['section_3']['links'][5]['link'] ?? ''); ?>">
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
                                                    <label>آدرس تلگرام:</label>
                                                    <input name="telegram" type="text"
                                                           class="form-control" placeholder=""
                                                           value="<?= set_value($setting['footer']['socials']['telegram'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mt-10">
                                                <div class="form-group">
                                                    <label>آدرس اینستاگرام:</label>
                                                    <input name="instagram" type="text"
                                                           class="form-control" placeholder=""
                                                           value="<?= set_value($setting['footer']['socials']['instagram'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mt-10">
                                                <div class="form-group">
                                                    <label>آدرس فیسبوک:</label>
                                                    <input name="facebook" type="text"
                                                           class="form-control" placeholder=""
                                                           value="<?= set_value($setting['footer']['socials']['facebook'] ?? ''); ?>">
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
                    <?php $this->view('templates/be/file-picker', $data); ?>
                    <!-- /standard width modal -->

                    <!-- Show active tab from url's hash -->
                    <script>
                        var hash = window.location.hash.substr(1);
                        var tabs = ['mainPanel', 'imagesPanel', 'footerPanel'];

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