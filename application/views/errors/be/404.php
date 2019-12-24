<!DOCTYPE html>
<html>

<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta charset="UTF-8"/>
    <meta name="robots" content="noindex,nofollow">
    <title>صفحه مورد نظر پیدا نشد</title>

    <!-- plugins:css -->
    <!-- inject:css -->
    <link href="<?= asset_url(); ?>be/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/style.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/pageFont.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?= asset_url(); ?>be/css/components.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="<?= asset_url(); ?>be/js/core/libraries/jquery.min.js"></script>

    <link href="<?= asset_url(); ?>fe/img/logo/favicon.png" type="images/x-icon" rel="shortcut icon">
</head>
<body class="login-container">
<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">

                <!-- Error title -->
                <div class="text-center content-group">
                    <h1 class="error-title">۴۰۴</h1>
                    <h5>صفحه مورد نظر پیدا نشد</h5>
                </div>
                <!-- /error title -->


                <!-- Error content -->
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
                        <div class="row">
                            <div class="col-xs-10 col-xs-push-1">
                                <?php if (isset($identity)): ?>
                                    <a href="<?= count(URITracker::get_tracks()) >= 2 ? URITracker::get_uri(-1) : base_url('admin/index'); ?>"
                                       class="btn btn-primary btn-block content-group">
                                        بازگشت به داشبورد
                                        <i class="icon-arrow-left12 position-right"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('index'); ?>"
                                       class="btn btn-primary btn-block content-group">
                                        بازگشت به سایت
                                        <i class="icon-arrow-left12 position-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /error wrapper -->

                <!-- Footer -->
                <?php $this->view('templates/be/copyright'); ?>
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
