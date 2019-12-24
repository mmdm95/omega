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
                        <img src="<?= asset_url("fe/img/avatars/{$identity->image}"); ?>" class="img-fit"
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
                            <i class="icon-price-tag2"></i>
                            <span>
                                برندها
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addBrand">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن برند
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageBrand">
                                    <i class="icon-price-tags" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده برندها
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!--                    <li>-->
                    <!--                        <a>-->
                    <!--                            <i class="icon-shield-check"></i>-->
                    <!--                            <span>-->
                    <!--                                گارانتی‌ها-->
                    <!--                            </span>-->
                    <!--                        </a>-->
                    <!--                        <ul>-->
                    <!--                            <li>-->
                    <!--                                <a href="--><?//= base_url(); ?><!--admin/addGuarantee">-->
                    <!--                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>-->
                    <!--                                    <small>-->
                    <!--                                        افزودن گارانتی-->
                    <!--                                    </small>-->
                    <!--                                </a>-->
                    <!--                            </li>-->
                    <!--                            <li>-->
                    <!--                                <a href="--><?//= base_url(); ?><!--admin/manageGuarantee">-->
                    <!--                                    <i class="icon-table2" style="font-size: 13px;"></i>-->
                    <!--                                    <small>-->
                    <!--                                        مشاهده گارانتی‌ها-->
                    <!--                                    </small>-->
                    <!--                                </a>-->
                    <!--                            </li>-->
                    <!--                        </ul>-->
                    <!--                    </li>-->
                    <li>
                        <a>
                            <i class="icon-truck"></i>
                            <span>
                                روش‌های ارسال
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addShipping">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن روش ارسال
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageShipping">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده روش‌های ارسال
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>
                            <i class="icon-coin-dollar"></i>
                            <span>
                                روش‌های پرداخت
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addPayment">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن روش پرداخت
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/managePayment">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده روش‌های پرداخت
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>
                            <i class="icon-percent"></i>
                            <span>
                                کوپن‌های تخفیف
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addCoupon">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن کوپن تخفیف
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageCoupon">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده کوپن‌های تخفیف
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>
                            <i class="icon-droplet"></i>
                            <span>
                                رنگ‌ها
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addColor">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن رنگ
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageColor">
                                    <i class="icon-droplets" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده رنگ‌ها
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="navigation-header"><span>نوشته‌ها</span> <i class="icon-menu"></i></li>
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
                    <li class="navigation-header"><span>فروشگاه</span> <i class="icon-menu"></i></li>
                    <li>
                        <a href="<?= base_url(); ?>admin/manageFactor">
                            <i class="icon-list2"></i>
                            <span>
                                سفارشات
                            </span>
                        </a>
                    </li>
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
                            <i class="icon-cart4"></i>
                            <span>
                                محصولات
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a>
                                    <i class="icon-circle"></i>
                                    <span>
                                        گروه‌بندی ویژگی‌ها
                                    </span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="<?= base_url(); ?>admin/addTitle">
                                            <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                            <small>
                                                افزودن گروه‌بندی ویژگی
                                            </small>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url(); ?>admin/manageTitle">
                                            <i class="icon-table2" style="font-size: 13px;"></i>
                                            <small>
                                                مشاهده گروه‌بندی ویژگی‌ها
                                            </small>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a>
                                    <i class="icon-circle"></i>
                                    <span>
                                        ویژگی‌ها
                                    </span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="<?= base_url(); ?>admin/addProperty">
                                            <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                            <small>
                                                افزودن ویژگی
                                            </small>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url(); ?>admin/manageProperty">
                                            <i class="icon-table2" style="font-size: 13px;"></i>
                                            <small>
                                                مشاهده ویژگی‌ها
                                            </small>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/addProduct">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن محصول
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageProduct">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده محصولات
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>
                            <i class="icon-magic-wand2"></i>
                            <span>
                                پیشنهادهای شگفت‌انگیز
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addFestival">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن پیشنهاد
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageFestival">
                                    <i class="icon-table2" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده پیشنهادها
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
                    <!-- /main -->
                    <!-- فرعی -->
                    <li class="navigation-header"><span>فرعی</span> <i class="icon-menu"></i></li>
                    <li>
                        <a>
                            <i class="icon-images2"></i>
                            <span>
                                اسلایدر
                            </span>
                        </a>
                        <ul>
                            <li>
                                <a href="<?= base_url(); ?>admin/addSlider">
                                    <i class="icon-add-to-list" style="font-size: 13px;"></i>
                                    <small>
                                        افزودن اسلاید
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url(); ?>admin/manageSlider">
                                    <i class="icon-images3" style="font-size: 13px;"></i>
                                    <small>
                                        مشاهده اسلایدها
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/manageContactUs">
                            <i class="icon-envelop5"></i>
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