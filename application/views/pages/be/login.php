<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $data['title'] ?></title>
    <!-- Global stylesheets -->
    <link href="<?= asset_url(); ?>be/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/style.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/pageFont.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <!-- Core JS files -->
    <script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?= asset_url(); ?>be/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?= asset_url(); ?>be/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->
    <!-- Theme JS files -->
    <script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?= asset_url(); ?>be/js/core/app.js"></script>
    <script type="text/javascript" src="<?= asset_url(); ?>be/js/pages/login.js"></script>
    <!-- /theme JS files -->
    <!-- Add fav icon -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?= $favIcon ?? ''; ?>">
    <link rel="icon" type="image/png" href="<?= $favIcon ?? ''; ?>">
</head>
<script>
    var baseUrl = '<?= base_url(); ?>';
</script>

<body class="login-container">
<!-- Page container -->
<div class="page-container">
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Content area -->
            <div class="content">
                <!-- Advanced login -->
                <form action="<?= base_url('admin/login'); ?>" method="post">
                    <?= $form_token; ?>

                    <div class="panel panel-body login-form">
                        <div class="text-center">
                            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                            <h5 class="content-group">
                                وارد حساب کاربری خود شوید!
                                <small class="display-block">اطلاعات شما</small>
                            </h5>
                        </div>

                        <?php if (isset($errors) && count($errors)): ?>
                            <div class="alert alert-danger bg-danger-400">
                                <ul class="list-unstyled">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?= $err; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="form-group has-feedback has-feedback-left">
                            <input required="required" type="text" class="form-control" placeholder="نام کاربری"
                                   name="username"
                                   value="<?= set_value($loginVals['username'] ?? ''); ?>">
                            <div class="form-control-feedback">
                                <i class="icon-mail-read text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group has-feedback has-feedback-left">
                            <input required="required" type="password" class="form-control" placeholder="رمز عبور"
                                   name="password">
                            <div class=" form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn bg-blue btn-block">
                                ورود
                                <i class="icon-arrow-left12 position-right"></i>
                            </button>
                        </div>
                        <div class="form-group login-options">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="styled" name="remember"
                                            <?= set_value($loginVals['remember'] ?? '', 'on', 'checked', '', '=='); ?>>
                                        مرا به خاطر بسپار
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /advanced login -->
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

</body>
</html>