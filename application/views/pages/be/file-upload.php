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
                            <i class="icon-circle position-left"></i> <span class="text-semibold">مدیریت فایل ها</span>
                        </h5>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="<?= base_url(); ?>admin/index"><i class="icon-home2 position-left"></i>
                                داشبورد
                            </a></li>
                        <li class="active">مدیریت فایل‌ها</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content">
                <!-- Removable thumbnails -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">مدیریت فایل ها</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="close"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <?php if ($data['upload']['allow_upload']): ?>
                                <div class="col-sm-12">
                                    <div id="file_drop_target">
                                        <div class="mb-5">
                                            <input type="file" class="file-styled-primary hidden" multiple
                                                   id="file-uploader">
                                            <label for="file-uploader" class="ml-5"
                                                   style="-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
                                                فایلی انتخاب نشده است
                                            </label>
                                            <label for="file-uploader" class="action btn btn-primary"
                                                   style="-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
                                                انتخاب فایل
                                            </label>
                                        </div>
                                        <strong class="display-block mb-5">یا</strong>
                                        فایل را کشیده و اینجا رها کنید
                                    </div>

                                    <div id="upload_progress"></div>
                                </div>
                            <?php endif; ?>
                            <?php if ($data['upload']['allow_create_folder']): ?>
                                <div class="col-sm-6 mt-10">
                                    <form action="?" method="post" id="mkdir">
                                        <label for="dirname" style="display: block;">
                                            ساخت پوشه جدید
                                            (Create New Folder):
                                        </label>
                                        <div class="form-group has-feedback has-feedback-left">
                                            <div class="input-group">
                                                <input id="dirname" class="form-control" type="text" name="name"
                                                       value="" placeholder="نام لاتین پوشه را وارد کنید">
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary">
                                                ساخت پوشه

                                                    </button>
                                                </span>
                                            </div>
                                            <div class=" form-control-feedback">
                                                <i class="icon-folder text-muted"></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-6 mt-10 mb-5">
                                <label style="display: block;">
                                    جابجایی موارد انتخاب شده
                                    (Move Selected Items):
                                </label>
                                <div class="form-group">
                                    <button id="selItem" type="button" class="btn btn-warning"
                                            data-toggle="modal" data-target="#modal_full">
                                        نمایش پوشه‌ها
                                        <i class="icon-folder position-right"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-20">
                                <label for="dirname" class="display-block">
                                    جستجو در پوشه فعلی:
                                </label>
                                <div class="form-group has-feedback has-feedback-left">
                                    <div>
                                        <input id="dirsearch" class="form-control" type="text"
                                               value="" placeholder="جستجو">
                                    </div>
                                    <div class=" form-control-feedback">
                                        <i class="icon-search4 text-muted"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div id="breadcrumb"></div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="table" class="table text-right">
                                <thead class="bg-indigo border-radius">
                                <tr>
                                    <th id="chks">
                                        <label class="checkbox-switch no-margin-bottom">
                                            <input type="checkbox" class="styled">
                                        </label>
                                    </th>
                                    <th class="sort_desc">نام</th>
                                    <th>اندازه</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>دسترسی ها</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody id="list">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /removable thumbnails -->

                <!-- Footer -->
                <?php $this->view("templates/be/copyright", $data); ?>
                <!-- /footer -->

                <!-- Standard width modal -->
                <div id="modal_full" class="modal fade">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title">
                                    انتخاب فولدر
                                </h5>
                            </div>

                            <div id="folders-body" class="modal-body">
                                <button id="tree-refresh" class="btn bg-purple btn-flat btn-icon btn-rounded"
                                        type="button">
                                    <i class="icon-rotate-cw3"></i>
                                </button>
                                <div class="tree tree-default well">
                                    <ul>
                                        <li>
                                            <a class="folder" data-path="<?= UPLOAD_PATH; ?>">
                                                <i class="folder-icon icon-folder"></i>
                                                Home
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">
                                    لغو
                                </button>
                                <button id="mvdir" type="button" class="btn btn-primary" data-dismiss="modal">
                                    جابجایی
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /standard width modal -->
            </div>
            <!-- /content area -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->