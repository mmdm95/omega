<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<div class="navbar navbar-inverse header-highlight">
    <div class="navbar-header">
        <a class="navbar-brand no-padding" href="<?= base_url(); ?>index">
            <img src="<?= $favIcon; ?>" alt="" class="p-10 img-full-y">
        </a>

        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li>
                <a class="sidebar-control sidebar-main-toggle hidden-xs">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <p class="navbar-text"><span class="label bg-success">خوش آمدید</span></p>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <img class="img-sm img-fit" style="max-height: none;"
                         src="<?= asset_url("{$identity->image}"); ?>" alt="">
                    <span>
                        <?= set_value($identity->first_name ?? '', '', null, $identity->username); ?>
                    </span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="<?= base_url('admin/editUser/' . @$identity->id); ?>">
                            <i class="icon-file-eye"></i>
                            تغییر مشخصات
                        </a>
                    </li>
                    <li class="divider"></li>
                    <?php if ($auth->isAllow('setting', 2)): ?>
                    <li><a href="<?= base_url(); ?>admin/setting"><i class="icon-cog5"></i>تنظیمات سایت</a></li>
                    <?php endif; ?>
                    <li><a href="<?= base_url('admin/logout'); ?>"><i class="icon-switch2"></i> خروج</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>