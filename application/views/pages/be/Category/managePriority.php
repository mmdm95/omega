<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!-- Main navbar -->
<?php $this->view("templates/be/mainnavbar", $data); ?>
<!-- /main navbar -->
<!-- Page container -->
<script>
    (function ($) {
        'use strict';

        $(function() {
            // Placeholder
            $( "#sortable-list-placeholder" ).sortable({
                placeholder: "sortable-placeholder",
                start: function(e, ui) {
                    ui.placeholder.height(ui.item.outerHeight());
                },
                update: function (e, ui) {
                    $('.sortable-list-item').each(function (i) {
                        var $this = $(this);
                        $this.find('.priority').val(i + 1);
                    });
                }
            });
        });
    })(jQuery);
</script>

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
                                class="text-semibold">تغییر اولویت</span>
                        </h5>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?= base_url(); ?>admin/index"><i class="icon-home2 position-left"></i>
                                داشبورد
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/manageCategory">
                                دسته‌بندی‌ها
                            </a>
                        </li>
                        <li class="active">تغییر اولویت</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?= base_url(); ?>admin/managePriority/<?= implode('/', $param); ?>" method="post">
                            <?= $data['form_token']; ?>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">
                                                تغییر اولویت سطح
                                                <?= $param[1]; ?>
                                            </h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <?php if (isset($errors) && count($errors)): ?>
                                                <div class="alert alert-danger alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <ul class="list-unstyled">
                                                        <?php foreach ($errors as $err): ?>
                                                            <li>
                                                                <i class="icon-dash" aria-hidden="true"></i>
                                                                <?= $err; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php elseif (isset($success)): ?>
                                                <div class="alert alert-success alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                                    <p>
                                                        <?= $success; ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>

                                            <div class="col-lg-12">
                                                <ul class="selectable-demo-list" id="sortable-list-placeholder">
                                                    <?php foreach ($items as $key => $item): ?>
                                                        <li class="sortable-list-item">
                                                            <input type="hidden" name="id[]" class="id"
                                                                   value="<?= $item['id']; ?>">
                                                            <input type="hidden" name="priority[]" class="priority"
                                                                   value="<?= !empty($item['priority']) ? $item['priority'] : ($key + 1); ?>">
                                                            <?= $item['category_name']; ?>
                                                            (
                                                            <span class="text-orange">
                                                                <?= $item['parent']; ?>
                                                            </span>
                                                            )
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>

                                            <div class="text-right col-md-12 mt-20">
                                                <a href="<?= base_url('admin/manageCategory'); ?>"
                                                   class="btn btn-default mr-5">
                                                    بازگشت
                                                </a>
                                                <button type="submit" class="btn btn-primary submit-button">
                                                    ذخیره
                                                    <i class="icon-arrow-left12 position-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer -->
                <?php $this->view("templates/be/copyright", $data); ?>
                <!-- /footer -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->