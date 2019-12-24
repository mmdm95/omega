<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76"
          href="<?= $favIcon; ?>">
    <link rel="icon" type="image/png"
          href="<?= $favIcon; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <title><?= $setting['main']['title'] ?? ''; ?></title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="<?= asset_url() ?>fe/fonts/font-awesome/css/font-awesome.min.css"/>
    <!-- CSS Files -->
    <link href="<?= asset_url() ?>fe/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?= asset_url() ?>fe/css/now-ui-kit.css" rel="stylesheet"/>
    <link href="<?= asset_url(); ?>fe/css/plugins/iziToast.css" rel="stylesheet" />
    <link href="<?= asset_url() ?>fe/css/main.css" rel="stylesheet"/>
</head>

<body class="index-page sidebar-collapse">
<script>
    var baseUrl = '<?= base_url(); ?>';
    var siteLogo = '<?= $logo ?? ''; ?>';
    //-----
    var siteAction = '<?= ACTION; ?>';
</script>

<?php $this->view('templates/fe/home-menu-responsive-part', $data); ?>

<div class="wrapper default">
    <!-- header -->
    <?php $this->view('templates/fe/home-menu-part', $data); ?>
    <!-- header -->

    <!-- main -->
    <?php $this->view('templates/fe/404/main'); ?>
    <!-- main -->

    <!-- footer -->
    <?php $this->view('templates/fe/home-footer-part', $data); ?>
    <!-- footer -->
</div>
</body>
<!--   Core JS Files   -->
<script src="<?= asset_url() ?>fe/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="<?= asset_url() ?>fe/js/core/popper.min.js" type="text/javascript"></script>
<script src="<?= asset_url() ?>fe/js/core/bootstrap.min.js" type="text/javascript"></script>
<!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
<script src="<?= asset_url() ?>fe/js/now-ui-kit.js" type="text/javascript"></script>
<!--  Jquery easing -->
<script src="<?= asset_url() ?>fe/js/plugins/jquery.easing.1.3.min.js" type="text/javascript"></script>
<!-- iziToast Js -->
<script src="<?= asset_url(); ?>fe/js/plugins/iziToast.js" type="text/javascript"></script>
<!-- Main Js -->
<script src="<?= asset_url(); ?>fe/js/main.js" type="text/javascript"></script>
<!-- Cart Js -->
<script src="<?= asset_url(); ?>fe/js/cartJs.js" type="text/javascript"></script>
</html>