<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!-- inject:js -->
<!-- Core JS files -->
<script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/loaders/pace.min.js"></script>

<script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/notifications/pnotify.min.js"></script>

<script type="text/javascript" src="<?= asset_url(); ?>be/js/core/libraries/bootstrap.min.js"></script>
<!-- /core JS files -->
<!-- Theme JS files -->
<script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="<?= asset_url(); ?>be/js/pages/form_layouts.js"></script>
<script type="text/javascript" src="<?= asset_url(); ?>be/js/pages/form_checkboxes_radios.js"></script>
<script type="text/javascript" src="<?= asset_url(); ?>be/js/plugins/loaders/lazy.min.js"></script>
<!-- /theme JS files -->
<!-- plugins:js -->
<?php if (isset($data['js']) && is_array(@$data['js']) && count(@$data['js']) > 0): ?>
    <?php foreach (@$data['js'] as $js): ?>
        <?= $js; ?>
    <?php endforeach; ?>
<?php endif; ?>
<!-- endinject -->

<script type="text/javascript" src="<?= asset_url(); ?>be/js/core/app.js"></script>