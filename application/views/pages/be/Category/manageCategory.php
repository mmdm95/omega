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
                                    class="text-semibold">دسته‌بندی‌ها</span>
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
                        <li class="active">دسته‌بندی‌ها</li>
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
                            <?php foreach ($levels as $level): ?>
                                <div class="col-lg-4 col-md-6">
                                    <a href="<?= base_url("admin/managePriority/level/{$level['level']}"); ?>"
                                       class="panel shadow-depth2 btn btn-lg btn-info btn-block">
                                        <h6>
                                            <i class="icon-list-ordered mr-10 img-sm p-10 img-circle bg-orange"
                                               aria-hidden="true"></i>
                                            تغییر اولویت دسته سطح
                                            <?= convertNumbersToPersian($level['level']); ?>
                                        </h6>
                                    </a>
                                </div>
                            <?php endforeach; ?>

                            <div class="col-sm-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">دسته‌بندی‌ها</h6>
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
                                                    <th>عنوان دسته‌بندی</th>
                                                    <th>دسته‌بندی والد</th>
                                                    <th>سطح</th>
                                                    <th>وضعیت نمایش</th>
                                                    <th>نمایش در منوها</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load categories data -->
                                                <?php foreach ($categories as $key => $category): ?>
                                                    <tr>
                                                        <td width="50px">
                                                            <?= convertNumbersToPersian($key + 1); ?>
                                                        </td>
                                                        <td>
                                                            <?= $category['category_name']; ?>
                                                        </td>
                                                        <td>
                                                            <?= $category['parent']; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($category['level'] == 1): ?>
                                                                سطح ۱
                                                            <?php elseif ($category['level'] == 2): ?>
                                                                سطح ۲
                                                            <?php elseif ($category['level'] == 3): ?>
                                                                سطح ۳
                                                            <?php else: ?>
                                                                <i class="icon-dash text-grey-300"
                                                                   aria-hidden="true"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($category['status'] == 1): ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-success">فعال</span>
                                                            <?php else: ?>
                                                                <span class="label label-striped no-border-top no-border-right no-border-bottom border-left
                                                                 border-left-lg border-left-danger">غیر فعال</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td width="150px">
                                                            <?php if ($category['deletable'] == 1): ?>
                                                                <input type="hidden" value="<?= $category['id']; ?>">
                                                                <input type="checkbox" class="switchery showInMenuParts"
                                                                    <?= set_value($category['show_in_menu'] ?? '', 1, 'checked', '', '=='); ?> />
                                                            <?php else: ?>
                                                                <i class="icon-minus2 text-grey-300"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="width: 115px;" class="text-center">
                                                            <?php if ($category['deletable'] == 1): ?>
                                                                <ul class="icons-list">
                                                                    <li class="text-primary-600">
                                                                        <a href="<?= base_url(); ?>admin/editCategory/<?= $category['id']; ?>"
                                                                           title="ویرایش" data-popup="tooltip">
                                                                            <i class="icon-pencil7"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li class="text-danger-600">
                                                                        <a class="deleteCategoryBtn"
                                                                           title="حذف" data-popup="tooltip">
                                                                            <input type="hidden"
                                                                                   value="<?= $category['id']; ?>">
                                                                            <i class="icon-trash"></i>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            <?php else: ?>
                                                                <i class="icon-minus2 text-grey-300"></i>
                                                            <?php endif; ?>
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