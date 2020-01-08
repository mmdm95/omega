<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!-- Main sidebar -->
<div class="sidebar sidebar-default sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <input type="hidden" id="PLATFORM" value="<?= PLATFORM; ?>">

        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <a href="<?= base_url() . 'admin/editUser/' . @$identity->id; ?>"
                       class="media-left">
                        <img src="<?= asset_url($identity->image); ?>" class="img-fit"
                             alt="">
                    </a>
                    <div class="media-body">
                        <a href="<?= base_url() . 'admin/editUser/' . @$identity->id; ?>"
                           class="media-heading text-semibold">
                            <?= set_value($identity->first_name ?? '', '', null, $identity->username); ?>
                        </a>
                        <div class="text-size-mini text-muted">
                            <div class="text-size-mini text-muted">
                                <?= $identity->role_desc ?? "<i class='icon-dash text-danger'></i>"; ?>
                            </div>
                        </div>
                    </div>
                    <div class="media-right media-middle">
                        <ul class="icons-list">
                            <li>
                                <a href="<?= base_url(); ?>index" target="_blank">
                                    <i class="icon-earth"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->
        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    <!-- Main -->
                    <li class="navigation-header"><span>مدیریت</span> <i class="icon-menu"></i></li>
                    <li>
                        <a href="<?= base_url(); ?>admin/index">
                            <i class="icon-home4"></i>
                            <span>داشبورد</span>
                        </a>
                    </li>
                    <?php if ($auth->isAllow('user', 2)): ?>
                        <li>
                            <a>
                                <i class="icon-users4"></i>
                                <span>کاربران</span>
                            </a>
                            <ul>
                                <li>
                                    <a href="<?= base_url(); ?>admin/manageUser">
                                        <i class="icon-users" style="font-size: 13px;"></i>
                                        <small>
                                            مشاهده کاربران
                                        </small>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a>
                            <i class="icon-user-block"></i>
                            <span>
                                بلاک لیست
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addBlockedUser">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageBlockedUser">
                                    <i class="icon-blocked" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/manageNewsletter">
                            <i class="icon-envelop5"></i>
                            <span>
                                خبرنامه
                            </span>
                        </a>
                    </li>
                    <li class="navigation-header"><span>نوشته‌ها</span> <i class="icon-menu"></i></li>
                    <li>
                        <a>
                            <i class="icon-tree6"></i>
                            <span>
                                دسته‌بندی
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addCategory">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن دسته‌بندی
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageCategory">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده دسته‌بندی‌ها
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>
                            <i class="icon-notebook"></i>
                            <span>
                                نوشته‌ها
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addArticle">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن نوشته
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageArticle">
                                    <i class="icon-pencil7" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده نوشته‌ها
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>
                            <i class="icon-archive"></i>
                            <span>
                                نوشته‌های ثابت
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addStaticPage">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن نوشته‌ ثابت
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageStaticPage">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده نوشته‌های ثابت
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/manageComment">
                            <i class="icon-comment-discussion"></i>
                            <span>
                                نظرات
                            </span>
                        </a>
                    </li>
                    <li class="navigation-header"><span>اصلی</span> <i class="icon-menu"></i></li>
                    <li>
                        <a>
                            <i class="icon-map4"></i>
                            <span>
                                مدیریت طرح
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addPlan">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن طرح
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/managePlan">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده طرح‌ها
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/manageFactor">
                            <i class="icon-list2"></i>
                            <span>
                                مشاهده فاکتورها
                            </span>
                        </a>
                    </li>
                    <!-- /main -->
                    <!-- فرعی -->
                    <li class="navigation-header"><span>فرعی</span> <i class="icon-menu"></i></li>
                    <li>
                        <a>
                            <i class="icon-link"></i>
                            <span>
                                لینک‌های مفید
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addUsefulLink">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن لینک
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageUsefulLink">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده لینک‌ها
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/manageFAQ">
                            <i class="icon-question3"></i>
                            <span>
                                سؤالات متداول
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/manageContactUs">
                            <i class="icon-phone2"></i>
                            <span>
                                تماس با ما
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/fileUpload">
                            <i class="icon-stack"></i>
                            <span>
                                مدیریت فایل‌ها
                            </span>
                        </a>
                    </li>
                    <?php if ($auth->isAllow('setting', 2)): ?>
                        <li>
                            <a href="<?= base_url(); ?>admin/setting">
                                <i class="icon-cogs"></i>
                                <span>
                                    تنظیمات سایت
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?= base_url(); ?>admin/logout">
                            <i class="icon-switch2"></i>
                            <span>
                                خروج
                            </span>
                        </a>
                    </li>
                    <!--                    فرعی-->
                </ul>
            </div>
        </div>
        <!-- /main navigation -->

    </div>
</div>
<!-- /main sidebar -->