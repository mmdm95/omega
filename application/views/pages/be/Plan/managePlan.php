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
                            <i class="icon-circle position-left"></i> <span
                                    class="text-semibold">مدیریت طرح‌ها</span>
                        </h5>
                    </div>
                </div>
                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?= base_url(); ?>admin/index">
                                <i class="icon-home2 position-left"></i>
                                داشبورد
                            </a>
                        </li>
                        <li class="active">طرح‌ها</li>
                    </ul>
                </div>
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">طرح‌ها</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered datatable-highlight">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>تصویر</th>
                                                    <th>عنوان</th>
                                                    <th>تاریخ شروع طرح</th>
                                                    <th>تاریخ پایان طرح</th>
                                                    <th>پر شده / ظرفیت کل</th>
                                                    <th>هزینه طرح</th>
                                                    <th>وضعیت</th>
                                                    <th>عملیات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Load categories data -->
                                                <?php foreach ($plans as $key => $plan): ?>
                                                    <tr>
                                                        <td width="50px">
                                                            <?= convertNumbersToPersian($key + 1); ?>
                                                        </td>
                                                        <td width="100px">
                                                            <a data-url="<?= base_url() . $plan['image']; ?>"
                                                               data-popup="lightbox">
                                                                <img src=""
                                                                     data-src="<?= base_url() . $plan['image']; ?>"
                                                                     alt="<?= $plan['title']; ?>"
                                                                     class="img-rounded img-preview lazy">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="<?= base_url('plan/' . $plan['title']); ?>">
                                                                <?= $plan['title']; ?>
                                                            </a>
                                                        </td>

                                                        <?php
                                                        $dateType = 'warning';
                                                        if ($plan['start_at'] >= time() && $plan['end_at'] <= time()) {
                                                            $dateType = 'success';
                                                        }
                                                        ?>
                                                        <td class="<?= $dateType; ?>">
                                                            <?= jDateTime::date('Y/m/d - H:i', $plan['start_at']); ?>
                                                        </td>
                                                        <td class="<?= $dateType; ?>">
                                                            <?= jDateTime::date('Y/m/d - H:i', $plan['end_at']); ?>
                                                        </td>

                                                        <td class="info">
                                                            <?= $plan['filled']; ?> / <?= $plan['capacity']; ?>
                                                        </td>
                                                        <td>
                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($plan['base_price'], true))); ?>
                                                        </td>
                                                        <td>
                                                            <?php switch ($plan['status']):
                                                                case PLAN_STATUS_ACTIVATE: ?>

                                                                    <?php break; ?>
                                                                <?php case PLAN_STATUS_DEACTIVATE: ?>

                                                                    <?php break; ?>
                                                                <?php case PLAN_STATUS_FULL: ?>

                                                                    <?php break; ?>
                                                                <?php case PLAN_STATUS_CLOSED: ?>

                                                                    <?php break; ?>
                                                                <?php endswitch; ?>
                                                        </td>

                                                        <td style="width: 115px;" class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="text-black">
                                                                    <a href="<?= base_url(); ?>admin/detailPlan/<?= $plan['id']; ?>"
                                                                       title="جزئیات" data-popup="tooltip">
                                                                        <i class="icon-eye"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="text-primary-600">
                                                                    <a href="<?= base_url(); ?>admin/editPlan/<?= $plan['id']; ?>"
                                                                       title="ویرایش" data-popup="tooltip">
                                                                        <i class="icon-pencil7"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="text-danger-600">
                                                                    <a class="deletePlanBtn"
                                                                       title="حذف" data-popup="tooltip">
                                                                        <input type="hidden"
                                                                               value="<?= $plan['id']; ?>">
                                                                        <i class="icon-trash"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /form centered -->
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