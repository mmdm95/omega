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
            <!-- Content area -->
            <div class="content">
                <!-- Dashboard content -->
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <label class="label label-default border-left border-left-xlg border-left-green-800 bg-green-600 p-10 mb-15">
                                <h6 class="no-margin">
                                    تاریخ امروز :
                                    <?= $todayDate; ?>
                                </h6>
                            </label>
                            <label class="mb-15"
                                   title="پیام‌های خوانده نشده" data-popup="tooltip" data-placement="bottom">
                                <a href="<?= base_url(); ?>admin/manageContactUs"
                                   class="text-white p-10 label bg-orange-600">
                                    <h6 class="no-margin">
                                        <i class="icon-envelop5 position-left" aria-hidden="true"></i>
                                        <?php if ($unreadContacts != 0): ?>
                                            <?= convertNumbersToPersian($unreadContacts); ?>
                                            عدد
                                        <?php else: ?>
                                            ندارید
                                        <?php endif; ?>
                                    </h6>
                                </a>
                            </label>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div>
                                    <h5 class="mb-15 text-grey-400">
                                        <i class="icon-circle-left2"></i>
                                        دسترسی سریع
                                    </h5>
                                </div>
                                <div class="pt-15 text-center my-main-page">
                                    <ul class="list-unstyled list-inline row">
                                        <?php if ($auth->isAllow('user', 2)): ?>
                                            <li class="col-xs-6 col-sm-4 col-md-3">
                                                <a href="<?= base_url(); ?>admin/manageUser" style="min-width: 130px;"
                                                   class="btn btn-info btn-float btn-float-lg border-left border-grey-300
                                        border-left-info-300 bg-white border-left-lg display-block panel no-border-radius">
                                                    <i class="icon-users4" aria-hidden="true"></i>
                                                    <span>
                                                    مدیریت کاربران
                                                </span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li class="col-xs-6 col-sm-4 col-md-3">
                                            <a href="<?= base_url(); ?>admin/manageCategory" style="min-width: 130px;"
                                               class="btn btn-info btn-float btn-float-lg border-left border-grey-300
                                        border-left-info-300 bg-white border-left-lg display-block panel no-border-radius">
                                                <i class="icon-hash" aria-hidden="true"></i>
                                                <span>
                                                    مشاهده دسته‌بندی‌ها
                                                </span>
                                            </a>
                                        </li>
                                        <li class="col-xs-6 col-sm-4 col-md-3">
                                            <a href="<?= base_url(); ?>admin/manageComment" style="min-width: 130px;"
                                               class="btn btn-info btn-float btn-float-lg border-left border-grey-300
                                        border-left-info-300 bg-white border-left-lg display-block panel no-border-radius">
                                                <i class="icon-comment-discussion" aria-hidden="true"></i>
                                                <span>
                                                    مدیریت نظرات
                                                </span>
                                            </a>
                                        </li>
                                        <li class="col-xs-6 col-sm-4 col-md-3">
                                            <a href="<?= base_url(); ?>admin/manageContactUs" style="min-width: 130px;"
                                               class="btn btn-info btn-float btn-float-lg border-left border-grey-300
                                        border-left-info-300 bg-white border-left-lg display-block panel no-border-radius">
                                                <i class="icon-envelop5" aria-hidden="true"></i>
                                                <span>
                                                    تماس با ما
                                                </span>
                                            </a>
                                        </li>
                                        <li class="col-xs-6 col-sm-4 col-md-3">
                                            <a href="<?= base_url(); ?>admin/fileUpload" style="min-width: 130px;"
                                               class="btn btn-info btn-float btn-float-lg border-left border-grey-300
                                        border-left-info-300 bg-white border-left-lg display-block panel no-border-radius">
                                                <i class="icon-stack" aria-hidden="true"></i>
                                                <span>
                                                    مدیریت فایل
                                                </span>
                                            </a>
                                        </li>
                                        <?php if ($auth->isAllow('setting', 2)): ?>
                                            <li class="col-xs-6 col-sm-4 col-md-3">
                                                <a href="<?= base_url(); ?>admin/setting" style="min-width: 130px;"
                                                   class="btn btn-info btn-float btn-float-lg border-left border-grey-300
                                        border-left-info-300 bg-white border-left-lg display-block panel no-border-radius">
                                                    <i class="icon-cogs" aria-hidden="true"></i>
                                                    <span>
                                                    تنظیمات
                                                </span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <hr>
                        </div>

                        <!-- Quick stats boxes -->
                        <div class="row">
                            <div class="col-sm-12 mb-10">
                                <h5 class="mb-15 text-grey-400">
                                    <i class="icon-circle-left2"></i>
                                    دید کلی
                                </h5>
                            </div>

                            <?php if($identity->role_id <= AUTH_ROLE_ADMIN): ?>
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="panel text-pink-400 border-top-lg border-top-pink-400">
                                    <div class="panel-body">
                                        <h3 class="no-margin">
                                            <?= convertNumbersToPersian($usersCount); ?>
                                        </h3>
                                        کاربر
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="panel text-indigo-400 border-top-lg border-top-indigo-400">
                                    <div class="panel-body">
                                        <h3 class="no-margin">
                                            <?= convertNumbersToPersian($staticPages); ?>
                                        </h3>
                                        نوشته‌ ثابت
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="panel text-indigo-600 border-top-lg border-top-indigo-600">
                                    <div class="panel-body">
                                        <h3 class="no-margin">
                                            <?= convertNumbersToPersian($factorsCount); ?>
                                        </h3>
                                        فاکتور
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="panel text-indigo-800 border-top-lg border-top-indigo-800">
                                    <div class="panel-body">
                                        <h3 class="no-margin">
                                            <?= convertNumbersToPersian($catsCount); ?>
                                        </h3>
                                        دسته‌بندی
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <div class="panel text-purple-600 border-top-lg border-top-purple-600">
                                    <div class="panel-body">
                                        <h3 class="no-margin">
                                            <?= convertNumbersToPersian($commentsCount); ?>
                                        </h3>
                                        نظر
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /quick stats boxes -->
                    </div>
                </div>
                <!-- /dashboard content -->

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