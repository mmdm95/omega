<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <?php if (isset($data['upload']['allow_create_folder']) && $data['upload']['allow_create_folder']): ?>
        <div class="col-sm-6">
            <form action="?" method="post" id="mkdir">
                <label for="dirname" style="display: block;">
                    ساخت پوشه جدید
                    (Create New Folder)
                </label>
                <div class="form-group has-feedback has-feedback-left">
                    <input id="dirname" class="form-control" type="text" name="name"
                           value="" placeholder="نام لاتین پوشه را وارد کنید">
                    <div class=" form-control-feedback">
                        <i class="icon-folder text-muted"></i>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn bg-blue">
                        ساخت پوشه
                        <i class="icon-arrow-left13 position-right"></i>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php if (isset($data['upload']['allow_upload']) && $data['upload']['allow_upload']): ?>
        <div class="col-sm-6">
            <div id="file_drop_target">
                <div class="uploader" style="margin-bottom: 10px;">
                    <input type="file" class="file-styled-primary" multiple>
                    <span class="filename"
                          style="-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
                                            فایلی انتخاب نشده است
                                        </span>
                    <span class="action btn bg-blue"
                          style="-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
                                            انتخاب فایل
                                        </span>
                </div>
                <b style="display: block;">یا</b>
                فایل را کشیده و اینجا رها کنید
            </div>

            <div id="upload_progress"></div>
        </div>
    <?php endif; ?>

    <div class="col-sm-12"></div>
    <div class="col-sm-6" style="margin-top: 10px;">
        <label for="dirname" style="display: block;">
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
        <div id="breadcrumb">&nbsp;</div>
    </div>
</div>

<div class="table-responsive">
    <table id="table">
        <thead class="bg-indigo">
        <tr>
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