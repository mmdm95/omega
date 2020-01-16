<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE; ?>">

<head>
    <meta charset="UTF-8">

    <link rel="apple-touch-icon" sizes="76x76" href="<?= $favIcon ?? ''; ?>">
    <link rel="icon" type="image/png" href="<?= $favIcon ?? ''; ?>">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport' />

    <title><?= $title ?? ''; ?></title>

    <!-- inject:css -->
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/bootstrap/bootstrap-rtl.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/brands.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/jquery-ui.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/line-awesome.min.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/magnific-popup.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/select2.min.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/slick.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/iziToast.css">
    <link rel="stylesheet" href="<?= asset_url(); ?>fe/css/style-rtl.css">
    <!-- endinject -->

    <!-- plugin css for this page -->
    <?php if (isset($css) && is_array(@$css) && count(@$css) > 0): ?>
        <?php foreach (@$css as $css): ?>
            <?= $css; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- End plugin css for this page -->

    <!-- inject:js -->
    <script src="<?= asset_url(); ?>fe/js/jquery/jquery-1.12.3.js"></script>
    <script src="<?= asset_url(); ?>fe/js/bootstrap/popper.js"></script>
    <script src="<?= asset_url(); ?>fe/js/bootstrap/bootstrap.min.js"></script>
    <!-- endinject -->
</head>
<body class="rtl">
<script>
    var baseUrl = '<?= base_url(); ?>';
    var siteLogo = '<?= $logo ?? ''; ?>';
    //-----
    var siteAction = '<?= ACTION; ?>';
</script>