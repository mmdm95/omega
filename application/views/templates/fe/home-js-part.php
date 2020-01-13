<!-- inject:js-->
<script src="<?= asset_url(); ?>fe/js/jquery-ui.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/jquery.barrating.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/jquery.counterup.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/jquery.magnific-popup.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/jquery.waypoints.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/masonry.pkgd.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/owl.carousel.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/select2.full.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/slick.min.js"></script>
<script src="<?= asset_url(); ?>fe/js/locator.js"></script>
<script src="<?= asset_url(); ?>fe/js/main.js"></script>
<!-- endinject-->

<!-- plugins:js -->
<?php if (isset($data['js']) && is_array(@$data['js']) && count(@$data['js']) > 0): ?>
    <?php foreach (@$data['js'] as $js): ?>
        <?= $js; ?>
    <?php endforeach; ?>
<?php endif; ?>
<!-- endinject -->