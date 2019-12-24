<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!doctype html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex,nofollow">
    <title>انتخاب فایل</title>
    <!-- plugins:css -->
    <link href="<?= asset_url(); ?>be/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/style.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/pageFont.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/colors.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/efm.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="<?= asset_url(); ?>be/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/loaders/lazy.min.js"></script>

    <link rel="shortcut icon" href="<?= asset_url(); ?>fe/img/logo/favicon.png"/>

    <style>
        #page {
            max-height: 600px;
        }
    </style>
</head>

<body>
<div id="page">
    <div class="row" style="margin: 0;">
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

    <input type="hidden" id="base" value="<?= base_url(); ?>">

    <form name="my_form" class="text-right">
        <input type="hidden" name="my_field"/>
        <input id="choose_file" type="button" class="btn btn-primary m-5 mt-15" value="انتخاب فایل">
    </form>
</div>

<script language="javascript" type="text/javascript">
    $(function () {
        $('#choose_file').on('click', function() {
            var selectedRow = $($('#table').find('.selectable').get(0));
            if(typeof selectedRow != typeof undefined && selectedRow.length && !selectedRow.hasClass('is_dir')) {
                var image_url = selectedRow.find('.first a').attr("data-url");

                top.tinymce.activeEditor.windowManager.getParams().onInsert(image_url);
                top.tinymce.activeEditor.windowManager.close();
            }
        });
    });

</script>

<?php include VIEW_PATH . 'templates/be/efm.php'; ?>
</body>
</html>