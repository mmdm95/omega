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
                                    class="text-semibold">جزئیات طرح</span>
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
                            <a href="<?= base_url(); ?>admin/managePlan">
                                مدیریت طرح‌ها
                            </a>
                        </li>
                        <li class="active">جزئیات طرح</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">افراد ثبت نام شده در طرح</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row panel-body">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered datatable-highlight">
                                                        <thead>
                                                        <tr class="bg-default">
                                                            <th>#</th>
                                                            <th>تصویر</th>
                                                            <th>نام کاربری</th>
                                                            <th>نام و نام خانوادگی</th>
                                                            <th>مبلغ پرداخت شده</th>
                                                            <th>تاریخ ذخیره طرح</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($planVals['buyers'] as $key => $buyer): ?>
                                                            <tr>
                                                                <td width="50px">
                                                                    <?= convertNumbersToPersian($key + 1); ?>
                                                                </td>
                                                                <td width="100px">
                                                                    <a data-url="<?= base_url() . $buyer['image']; ?>"
                                                                       data-popup="lightbox">
                                                                        <img src=""
                                                                             data-src="<?= base_url() . $buyer['image']; ?>"
                                                                             alt="<?= $buyer['title']; ?>"
                                                                             class="img-rounded img-preview lazy">
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <?= mb_strlen($buyer['username']) == 11 ? convertNumbersToPersian($buyer['username']) : $buyer['username']; ?>
                                                                </td>
                                                                <td><?= $buyer['full_name']; ?></td>
                                                                <td>
                                                                    <?= convertNumbersToPersian(number_format(convertNumbersToPersian($buyer['payed_amount'], true))); ?>
                                                                </td>
                                                                <td>
                                                                    <?= jDateTime::date('j F Y در ساعت H:i', $buyer['created_at']); ?>
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

                                <div class="panel panel-white panel-collapsed">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">مشخصات طرح</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-6 col-lg-push-3 mb-20">
                                            <img src="<?= set_value($planVals['image'] ?? '', '', base_url($planVals['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                 class="img-rounded img-full-x" alt=""
                                                 style="object-fit: contain;"
                                                 data-base-url="<?= base_url(); ?>">
                                        </div>

                                        <div class="col-lg-12"></div>

                                        <div class="col-lg-4 border border-default p-20">
                                            <label>عنوان طرح:</label>
                                            <p class="text-primary-600">
                                                <?= $planVals['title']; ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>کل ظرفیت:</label>
                                            <p class="text-primary-600">
                                                <?= convertNumbersToPersian($planVals['capacity']); ?>
                                                نفر
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>ظرفیت خریداری شده:</label>
                                            <p class="text-primary-600">
                                                <?= convertNumbersToPersian($planVals['filled']); ?>
                                                نفر
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>قیمت کل طرح (تومان):</label>
                                            <p class="text-primary-600">
                                                <?= convertNumbersToPersian(number_format(convertNumbersToPersian($planVals['total_price'], true))) . ' تومان'; ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>قیمت ورودی طرح (تومان):</label>
                                            <p class="text-primary-600">
                                                <?= convertNumbersToPersian(number_format(convertNumbersToPersian($planVals['base_price'], true))) . ' تومان'; ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>قیمت پیش‌پرداخت (تومان):</label>
                                            <p class="text-primary-600">
                                                <?= convertNumbersToPersian(number_format(convertNumbersToPersian($planVals['min_price'], true))) . ' تومان'; ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>تاریخ شروع ثبت نام طرح:</label>
                                            <p class="text-primary-600">
                                                <time datetime="<?= date('Y-m-d H:i', $planVals['active_at']); ?>"
                                                      class="atbd_info iranyekan-light">
                                                    <?= jDateTime::date('j F Y در ساعت H:i', $planVals['active_at']); ?>
                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>تاریخ پایان ثبت نام طرح:</label>
                                            <p class="text-primary-600">
                                                <time datetime="<?= date('Y-m-d H:i', $planVals['deactive_at']); ?>"
                                                      class="atbd_info iranyekan-light">
                                                    <?= jDateTime::date('j F Y در ساعت H:i', $planVals['deactive_at']); ?>
                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>تاریخ شروع طرح:</label>
                                            <p class="text-primary-600">
                                                <time datetime="<?= date('Y-m-d H:i', $planVals['start_at']); ?>"
                                                      class="atbd_info iranyekan-light">
                                                    <?= jDateTime::date('j F Y در ساعت H:i', $planVals['start_at']); ?>
                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-lg-4 border border-default p-20">
                                            <label>تاریخ پایان طرح:</label>
                                            <p class="text-primary-600">
                                                <time datetime="<?= date('Y-m-d H:i', $planVals['end_at']); ?>"
                                                      class="atbd_info iranyekan-light">
                                                    <?= jDateTime::date('j F Y در ساعت H:i', $planVals['end_at']); ?>
                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-lg-8 border border-default p-20">
                                            <label>مخاطب طرح:</label>
                                            <p class="text-primary-600">
                                                <?php
                                                $audience = explode(',', $planVals['contact']);
                                                ?>
                                                <?php foreach ($audience as $a): ?>
                                                    <span class="label label-default">
                                                        <?= EDU_GRADES[$a]; ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-white panel-collapsed">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">درباره طرح</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row panel-body">
                                            <div class="col-md-12">
                                                <p class="text-justify no-margin">
                                                    <?= set_value($planVals['description'] ?? ''); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-white panel-collapsed">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">محل برگزاری</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-md-12 mt-12">
                                            <label>محل برگزاری:</label>
                                            <p><?= set_value($planVals['place'] ?? ''); ?></p>
                                            <hr>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label>شماره‌های پشتیبانی:</label>
                                            <?php
                                            $supportPhone = explode(',', $planVals['support_phone']);
                                            ?>
                                            <?php foreach ($supportPhone as $sp): ?>
                                                <span class="label label-default">
                                                    <?= convertNumbersToPersian($sp); ?>
                                                </span>
                                            <?php endforeach; ?>
                                            <hr>
                                        </div>
                                        <div class="form-group col-md-12 mt-12">
                                            <label>مکان پشتیبانی:</label>
                                            <p><?= set_value($planVals['support_place'] ?? ''); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-white panel-collapsed">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">قوانین طرح</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 mt-12">
                                                <p><?= set_value($planVals['rules'] ?? ''); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-white panel-collapsed">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">گالری تصاویر</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                            <!-- Indicators -->
                                            <ol class="carousel-indicators">
                                                <?php foreach ($planVals['image_gallery'] as $k => $img): ?>
                                                    <li data-target="#myCarousel" data-slide-to="<?= $k; ?>"
                                                        class="<?= $k == 0 ? 'active' : ''; ?>"></li>
                                                <?php endforeach; ?>
                                            </ol>

                                            <!-- Wrapper for slides -->
                                            <div class="carousel-inner">
                                                <?php foreach ($planVals['image_gallery'] as $k => $img): ?>
                                                    <div class="item <?= $k == 0 ? 'active' : ''; ?>">
                                                        <img src="<?= base_url($img); ?>" alt="<?= $img; ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <!-- Left and right controls -->
                                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-right"></span>
                                                <span class="sr-only">قبلی</span>
                                            </a>
                                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-left"></span>
                                                <span class="sr-only">بعدی</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <?php if (count($planVals['video_gallery'])): ?>
                                    <div class="panel panel-white panel-collapsed">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">گالری ویدیوها</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                                <!-- Indicators -->
                                                <ol class="carousel-indicators">
                                                    <?php foreach ($planVals['video_gallery'] as $k => $video): ?>
                                                        <li data-target="#myCarousel" data-slide-to="<?= $k; ?>"
                                                            class="<?= $k == 0 ? 'active' : ''; ?>"></li>
                                                    <?php endforeach; ?>
                                                </ol>

                                                <!-- Wrapper for slides -->
                                                <div class="carousel-inner">
                                                    <?php foreach ($planVals['video_gallery'] as $k => $video): ?>
                                                        <div class="item <?= $k == 0 ? 'active' : ''; ?>">
                                                            <video>
                                                                <source src="<?= base_url($video); ?>">
                                                            </video>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>

                                                <!-- Left and right controls -->
                                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                                    <span class="sr-only">قبلی</span>
                                                </a>
                                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                                    <span class="sr-only">بعدی</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">آپشن‌های طرح</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <?php foreach ($planVals['options'] as $key => $option): ?>
                                            <div class="pl-20 pr-20 pt-5 pb-5 bg-slate-400">
                                                <h4 class="iranyekan-regular m-0">
                                                    <?= $option['title']; ?>
                                                </h4>
                                            </div>

                                            <?php
                                            $isRadio = $option['radio'] == 2 ? true : false;
                                            ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered mb-20">
                                                    <thead>
                                                    <tr class="bg-default">
                                                        <th style="border: 1px solid #ddd;"><strong>عنوان و
                                                                توضیح</strong></th>
                                                        <th style="border: 1px solid #ddd;"><strong>قیمت</strong></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($option['name'] as $k2 => $name): ?>
                                                        <tr>
                                                            <td>
                                                                <h4><?= $name; ?></h4>
                                                                <?php if (!empty($option['desc'][$k2])): ?>
                                                                    <p class="no-margin">
                                                                        <?= $option['desc'][$k2]; ?>
                                                                    </p>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td width="35%">
                                                                <?php if (is_numeric($option['price'][$k2])): ?>
                                                                    <?= convertNumbersToPersian(number_format(convertNumbersToPersian($option['price'][$k2], true))); ?>
                                                                    تومان
                                                                <?php else: ?>
                                                                    <?= $option['price'][$k2]; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Standard width modal -->
                <?php $this->view('templates/be/file-picker', $data) ?>
                <!-- /standard width modal -->

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