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
                                    class="text-semibold">افزودن طرح</span>
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
                        <li class="active">افزودن طرح جدید</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?= base_url(); ?>admin/addPlan" method="post">
                            <?= $data['form_token']; ?>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">مشخصات طرح</h6>
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

                                            <div class="form-group col-lg-12">
                                                <div class="cursor-pointer pick-file border border-lg border-default"
                                                     data-toggle="modal"
                                                     data-target="#modal_full"
                                                     style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                    <input class="image-file" type="hidden"
                                                           name="image"
                                                           value="<?= set_value($planVals['image'] ?? ''); ?>">
                                                    <div class="media stack-media-on-mobile">
                                                        <div class="media-left">
                                                            <div class="thumb">
                                                                <a class="display-inline-block"
                                                                   style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                    <img
                                                                            src="<?= set_value($planVals['image'] ?? '', '', base_url($planVals['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                            class="img-rounded" alt=""
                                                                            style="width: 100px; height: 100px; object-fit: contain;"
                                                                            data-base-url="<?= base_url(); ?>">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="media-heading">
                                                                <a class="text-grey-300">
                                                                    <span class="text-danger">*</span>
                                                                    انتخاب تصویر شاخص:
                                                                </a>
                                                                <a class="io-image-name display-block">
                                                                    <?= basename(set_value($planVals['image'] ?? '')); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-8">
                                                <span class="text-danger">*</span>
                                                <label>عنوان طرح:</label>
                                                <input name="title" type="text" class="form-control"
                                                       placeholder="اجباری" required
                                                       value="<?= set_value($planVals['title'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <span class="text-danger">*</span>
                                                <label>ظرفیت:</label>
                                                <input name="capacity" type="text" class="form-control"
                                                       maxlength="4"
                                                       placeholder="مثال: ۳۰" required
                                                       value="<?= set_value($planVals['capacity'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <span class="text-danger">*</span>
                                                <label>هزینه کل طرح (تومان):</label>
                                                <input name="total_price" type="text" class="form-control"
                                                       placeholder="مثال: ۱۰۰۰۰۰۰" required
                                                       value="<?= set_value($planVals['total_price'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <span class="text-danger">*</span>
                                                <label>مبلغ ورودی طرح (تومان):</label>
                                                <input name="base_price" type="text" class="form-control"
                                                       placeholder="مثال: ۱۰۰۰۰۰" required
                                                       value="<?= set_value($planVals['base_price'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <span class="text-danger">*</span>
                                                <label>هزینه پیش‌پرداخت (تومان):</label>
                                                <input name="min_price" type="text" class="form-control"
                                                       placeholder="مثال: ۳۰۰۰۰۰" required
                                                       value="<?= set_value($planVals['min_price'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ شروع ثبت نام طرح:</label>
                                                <input type="hidden" name="active_date" id="altDateFieldActive">
                                                <input type="text" class="form-control range-from"
                                                       placeholder="تاریخ شروع ثبت نام طرح" readonly data-time="true"
                                                       data-alt-field="#altDateFieldActive"
                                                       data-format="YYYY/MM/DD - HH:mm" required
                                                       value="<?= set_value($planVals['active_date'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ پایان ثبت نام طرح:</label>
                                                <input type="hidden" name="deactive_date" id="altDateFieldDeactive">
                                                <input type="text" class="form-control range-to"
                                                       placeholder="تاریخ پایان ثبت نام طرح" readonly data-time="true"
                                                       data-alt-field="#altDateFieldDeactive"
                                                       data-format="YYYY/MM/DD - HH:mm" required
                                                       value="<?= set_value($planVals['deactive_date'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ شروع طرح:</label>
                                                <input type="hidden" name="start_date" id="altDateFieldStart">
                                                <input type="text" class="form-control range-from"
                                                       placeholder="تاریخ شروع طرح" readonly data-time="true"
                                                       data-alt-field="#altDateFieldStart"
                                                       data-format="YYYY/MM/DD - HH:mm" required
                                                       value="<?= set_value($planVals['start_date'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ پایان طرح:</label>
                                                <input type="hidden" name="end_date" id="altDateFieldEnd">
                                                <input type="text" class="form-control range-to"
                                                       placeholder="تاریخ پایان طرح" readonly data-time="true"
                                                       data-alt-field="#altDateFieldEnd"
                                                       data-format="YYYY/MM/DD - HH:mm" required
                                                       value="<?= set_value($planVals['end_date'] ?? ''); ?>">
                                            </div>
                                            <div class="col-lg-12">
                                                <span class="text-danger">*</span>
                                                <label>مخاطب طرح:</label>
                                                <select class="select-rtl" multiple="multiple"
                                                        name="audience[]" data-placeholder="انتخاب کنید">
                                                    <?php foreach (EDU_GRADES as $id => $grade): ?>
                                                        <option value="<?= $id; ?>"
                                                            <?= in_array($id, $planVals['audience'] ?? []) ? 'selected' : ''; ?>>
                                                            <?= $grade; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">درباره طرح</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mt-12">
                                                <textarea
                                                        class="form-control cntEditor"
                                                        placeholder="توضیحات"
                                                        name="description"
                                                        rows="10"><?= set_value($planVals['description'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-white">
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
                                                <span class="text-danger">*</span>
                                                <label>محل برگزاری:</label>
                                                <textarea
                                                        style="min-height: 100px; height: 120px; resize: vertical;"
                                                        class="form-control"
                                                        placeholder="محل برگزاری"
                                                        name="place" required
                                                        rows="10"><?= set_value($planVals['place'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <span class="text-danger">*</span>
                                                <label>شماره‌های پشتیبانی:</label>
                                                <input name="support_phone" type="text"
                                                       class="form-control" placeholder="Press Enter"
                                                       data-role="tagsinput" required
                                                       value="<?= set_value($planVals['support_phone'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-md-12 mt-12">
                                                <label>مکان پشتیبانی:</label>
                                                <textarea
                                                        style="min-height: 100px; height: 120px; resize: vertical;"
                                                        class="form-control"
                                                        placeholder="مکان پشتیبانی"
                                                        name="support_place"
                                                        rows="10"><?= set_value($planVals['support_place'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">قوانین طرح</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mt-12">
                                                <textarea
                                                        class="form-control cntEditor"
                                                        placeholder=""
                                                        name="rules"
                                                        rows="10"><?= set_value($planVals['rules'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">تصاویر بروشور</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body slide-items">
                                            <div class="col-sm-12 mb-20 text-right">
                                                <a href="javascript:void(0);"
                                                   class="btn btn-primary btn-icon add-slide-image ml-5"
                                                   title="اضافه کردن بروشور جدید" data-popup="tooltip">
                                                    <i class="icon-plus2" aria-hidden="true"></i>
                                                </a>
                                            </div>

                                            <?php if (count($errors) && count($planVals['brochure_gallery'])): ?>
                                                <?php foreach ($planVals['brochure_gallery'] as $key => $img): ?>
                                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-15 slide-item">
                                                        <div class="cursor-pointer pick-file border border-lg border-default"
                                                             data-toggle="modal"
                                                             data-target="#modal_full"
                                                             style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                            <input class="image-file" type="hidden"
                                                                   name="brochure_gallery[]"
                                                                   value="<?= set_value($img ?? ''); ?>">
                                                            <div class="media stack-media-on-mobile">
                                                                <div class="media-left">
                                                                    <div class="thumb">
                                                                        <a class="display-inline-block"
                                                                           style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                            <img
                                                                                    src="<?= set_value($img ?? '', '', base_url($img ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                                    class="img-rounded" alt=""
                                                                                    style="width: 100px; height: 100px; object-fit: contain;"
                                                                                    data-base-url="<?= base_url(); ?>">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="media-body">
                                                                    <h6 class="media-heading">
                                                                        <a class="io-image-lbl text-grey-300">
                                                                            انتخاب تصویر <?= ($key + 1); ?>
                                                                        </a>
                                                                        <a class="io-image-name display-block">
                                                                            <?= basename($img); ?>
                                                                        </a>
                                                                    </h6>
                                                                </div>
                                                                <?php if ($key == 0): ?>
                                                                    <small class="clear-img-val">&times;</small>
                                                                <?php else: ?>
                                                                    <small class="delete-new-image btn btn-danger">
                                                                        &times;
                                                                    </small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-15 slide-item">
                                                    <div class="cursor-pointer pick-file border border-lg border-default"
                                                         data-toggle="modal"
                                                         data-target="#modal_full"
                                                         style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                        <input class="image-file" type="hidden"
                                                               name="brochure_gallery[]"
                                                               value="">
                                                        <div class="media stack-media-on-mobile">
                                                            <div class="media-left">
                                                                <div class="thumb">
                                                                    <a class="display-inline-block"
                                                                       style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                        <img
                                                                                src="<?= asset_url('be/images/placeholder.jpg'); ?>"
                                                                                class="img-rounded" alt=""
                                                                                style="width: 100px; height: 100px; object-fit: contain;"
                                                                                data-base-url="<?= base_url(); ?>">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="media-heading">
                                                                    <a class="io-image-lbl text-grey-300">
                                                                        انتخاب تصویر 1
                                                                    </a>
                                                                    <a class="io-image-name display-block">
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <small class="clear-img-val">&times;</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">گالری تصاویر</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body slide-items">
                                            <div class="col-sm-12 mb-20 text-left">
                                                <div class="display-inline-block alert alert-primary
                                                no-border-right no-border-top no-border-bottom border-lg p-10 no-margin-bottom"
                                                     style="width: calc(100% - 50px);">
                                                    انتخاب حداقل یک تصویر اجباری است
                                                </div>
                                                <a href="javascript:void(0);"
                                                   class="btn btn-primary btn-icon add-slide-image ml-5"
                                                   title="اضافه کردن تصویر جدید" data-popup="tooltip">
                                                    <i class="icon-plus2" aria-hidden="true"></i>
                                                </a>
                                            </div>

                                            <?php if (count($errors) && count($planVals['image_gallery'])): ?>
                                                <?php foreach ($planVals['image_gallery'] as $key => $img): ?>
                                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-15 slide-item">
                                                        <div class="cursor-pointer pick-file border border-lg border-default"
                                                             data-toggle="modal"
                                                             data-target="#modal_full"
                                                             style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                            <input class="image-file" type="hidden"
                                                                   name="image_gallery[]"
                                                                   value="<?= set_value($img ?? ''); ?>">
                                                            <div class="media stack-media-on-mobile">
                                                                <div class="media-left">
                                                                    <div class="thumb">
                                                                        <a class="display-inline-block"
                                                                           style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                            <img
                                                                                    src="<?= set_value($img ?? '', '', base_url($img ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
                                                                                    class="img-rounded" alt=""
                                                                                    style="width: 100px; height: 100px; object-fit: contain;"
                                                                                    data-base-url="<?= base_url(); ?>">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="media-body">
                                                                    <h6 class="media-heading">
                                                                        <a class="io-image-lbl text-grey-300">
                                                                            انتخاب تصویر <?= ($key + 1); ?>
                                                                        </a>
                                                                        <a class="io-image-name display-block">
                                                                            <?= basename($img); ?>
                                                                        </a>
                                                                    </h6>
                                                                </div>
                                                                <?php if ($key == 0): ?>
                                                                    <small class="clear-img-val">&times;</small>
                                                                <?php else: ?>
                                                                    <small class="delete-new-image btn btn-danger">
                                                                        &times;
                                                                    </small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-15 slide-item">
                                                    <div class="cursor-pointer pick-file border border-lg border-default"
                                                         data-toggle="modal"
                                                         data-target="#modal_full"
                                                         style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                        <input class="image-file" type="hidden"
                                                               name="image_gallery[]"
                                                               value="">
                                                        <div class="media stack-media-on-mobile">
                                                            <div class="media-left">
                                                                <div class="thumb">
                                                                    <a class="display-inline-block"
                                                                       style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                        <img
                                                                                src="<?= asset_url('be/images/placeholder.jpg'); ?>"
                                                                                class="img-rounded" alt=""
                                                                                style="width: 100px; height: 100px; object-fit: contain;"
                                                                                data-base-url="<?= base_url(); ?>">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="media-heading">
                                                                    <a class="io-image-lbl text-grey-300">
                                                                        انتخاب تصویر 1
                                                                    </a>
                                                                    <a class="io-image-name display-block">
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <small class="clear-img-val">&times;</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">گالری ویدیوها</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body slide-items">
                                            <div class="col-sm-12 mb-20 text-right">
                                                <a href="javascript:void(0);"
                                                   class="btn btn-primary btn-icon add-slide-image ml-5"
                                                   title="اضافه کردن ویدیو جدید" data-popup="tooltip">
                                                    <i class="icon-plus2" aria-hidden="true"></i>
                                                </a>
                                            </div>

                                            <?php if (count($errors) && count($planVals['video_gallery'])): ?>
                                                <?php foreach ($planVals['video_gallery'] as $key => $vid): ?>
                                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-15 slide-item">
                                                        <div class="cursor-pointer pick-file-video border border-lg border-default"
                                                             data-toggle="modal"
                                                             data-target="#modal_full"
                                                             style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                            <input class="image-file" type="hidden"
                                                                   name="video_gallery[]"
                                                                   value="<?= set_value($vid ?? ''); ?>">
                                                            <div class="media stack-media-on-mobile">
                                                                <div class="media-left">
                                                                    <div class="thumb">
                                                                        <a class="display-inline-block"
                                                                           style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                            <img
                                                                                    src="<?= set_value($vid ?? '', '', asset_url('images/file-icons/Video.png'), asset_url('be/images/video-placeholder.png')); ?>"
                                                                                    class="img-rounded" alt=""
                                                                                    style="width: 100px; height: 100px; object-fit: contain;"
                                                                                    data-base-url="<?= base_url(); ?>">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="media-body">
                                                                    <h6 class="media-heading">
                                                                        <a class="io-image-lbl text-grey-300">
                                                                            انتخاب ویدیو <?= ($key + 1); ?>
                                                                        </a>
                                                                        <a class="io-image-name display-block">
                                                                            <?= basename($vid); ?>
                                                                        </a>
                                                                    </h6>
                                                                </div>
                                                                <?php if ($key == 0): ?>
                                                                    <small class="clear-video-val">&times;</small>
                                                                <?php else: ?>
                                                                    <small class="delete-new-image btn btn-danger">
                                                                        &times;
                                                                    </small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-15 slide-item">
                                                    <div class="cursor-pointer pick-file-video border border-lg border-default"
                                                         data-toggle="modal"
                                                         data-target="#modal_full"
                                                         style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                        <input class="image-file" type="hidden"
                                                               name="video_gallery[]"
                                                               value="">
                                                        <div class="media stack-media-on-mobile">
                                                            <div class="media-left">
                                                                <div class="thumb">
                                                                    <a class="display-inline-block"
                                                                       style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                        <img
                                                                                src="<?= asset_url('be/images/video-placeholder.png'); ?>"
                                                                                class="img-rounded" alt=""
                                                                                style="width: 100px; height: 100px; object-fit: contain;"
                                                                                data-base-url="<?= base_url(); ?>">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="media-heading">
                                                                    <a class="io-image-lbl text-grey-300">
                                                                        انتخاب ویدیو 1
                                                                    </a>
                                                                    <a class="io-image-name display-block">
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <small class="clear-video-val">&times;</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

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
                                            <div class="form-group">
                                                <div class="help-block alert alert-info no-border-right no-border-top no-border-bottom border-lg p-10">
                                                    <h5>
                                                        توجه:
                                                    </h5>
                                                    <p>
                                                        <i class="icon-dash" aria-hidden="true"></i>
                                                        برای افزودن گروه‌بندی جدید، دکمه
                                                        <span class="text-success-600">
                                                            سبز رنگ
                                                        </span>
                                                        را فشار دهید.
                                                    </p>
                                                    <p>
                                                        <i class="icon-dash" aria-hidden="true"></i>
                                                        همچنین برای افزودن آپشن جدید، دکمه
                                                        <span class="text-blue">
                                                            آبی رنگ
                                                        </span>
                                                        در هر آپشن را فشار دهید.
                                                    </p>
                                                    <p>
                                                        <i class="icon-dash" aria-hidden="true"></i>
                                                        در صورت انتخاب مورد اجباری، تمامی آیتم‌ها انتخاب شده و قابلیت عدم انتخاب را ندارند.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="property-items">
                                                <?php if (count($errors) && count($planVals['option_group'])): ?>
                                                    <?php foreach ($planVals['option_group'] as $k => $val): ?>
                                                        <div class="col-md-12 form-group position-relative property-item
                                                        border border-dashed border-default border-radius p-20 mt-10">
                                                            <div class="property-operation-container"
                                                                 style="top: -15px; left: -15px;">
                                                                <?php if ($k == 0): ?>
                                                                    <a href="javascript:void(0);"
                                                                       title="افزودن گروه‌بندی"
                                                                       class="btn bg-success-400 btn-icon btn-rounded shadow-depth1
                                                                          property-operation-add no-margin">
                                                                        <i class="icon-plus2" aria-hidden="true"></i>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <a href="javascript:void(0);" title="حذف گروه‌بندی"
                                                                       class="btn btn-danger btn-icon btn-rounded shadow-depth1
                                                                          property-operation-delete no-margin">
                                                                        <i class="icon-trash" aria-hidden="true"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>

                                                            <div class="row position-relative mb-20">
                                                                <div class="col-xs-6 col-sm-6 pt-20">
                                                                    <label>عنوان گروه:</label>
                                                                    <input type="text"
                                                                           name="option_group[<?= $k; ?>][title]"
                                                                           class="form-control p-item-input"
                                                                           value="<?= set_value($val['title'] ?? ''); ?>">
                                                                </div>
                                                                <div class="col-xs-3 col-sm-4 pt-20">
                                                                    <div class="form-check form-check-right pt-20">
                                                                        <label class="form-check-label ltr">
                                                                            <span class="rtl text-indigo">
                                                                                چند انتخابی
                                                                            </span>
                                                                            <input type="radio" class="selection-radio-<?= $k; ?>"
                                                                                   checked="checked"
                                                                                   name="option_group[<?= $k; ?>][radio]"
                                                                                   value="1"
                                                                                <?= set_value($val['radio'] ?? '', '1', 'checked', '', '=='); ?>>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check form-check-right pt-20">
                                                                        <label class="form-check-label ltr">
                                                                            <span class="rtl text-indigo">
                                                                                تک انتخابی
                                                                            </span>
                                                                            <input type="radio" class="selection-radio-<?= $k; ?>"
                                                                                   name="option_group[<?= $k; ?>][radio]"
                                                                                   value="2"
                                                                                <?= set_value($val['radio'] ?? '', '2', 'checked', '', '=='); ?>>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-3 col-sm-2 pt-20">
                                                                    <div class="form-check form-check-right pt-20">
                                                                        <label class="form-check-label ltr">
                                                                            <span class="rtl text-indigo">
                                                                                اختیاری
                                                                            </span>
                                                                            <input type="radio" class="action-toggle"
                                                                                   name="option_group[<?= $k; ?>][forced]"
                                                                                   data-action-toggle-el=".selection-radio-<?= $k; ?>"
                                                                                   data-action-toggle="true"
                                                                                   data-action="enable"
                                                                                   value="1"
                                                                                <?= set_value($val['forced'] ?? '', '2', 'checked', '', '=='); ?>>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check form-check-right pt-20">
                                                                        <label class="form-check-label ltr">
                                                                            <span class="rtl text-indigo">
                                                                                اجباری
                                                                            </span>
                                                                            <input type="radio" class="action-toggle"
                                                                                   name="option_group[<?= $k; ?>][forced]"
                                                                                   data-action-toggle-el=".selection-radio-<?= $k; ?>"
                                                                                   data-action-toggle="false"
                                                                                   data-action="enable"
                                                                                   value="2"
                                                                                <?= set_value($val['forced'] ?? '', '2', 'checked', '', '=='); ?>>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <?php foreach ($val['name'] as $k2 => $v): ?>
                                                                <div class="row position-relative property-each-item"
                                                                     style="padding-left: 40px;">
                                                                    <div class="col-xs-12"></div>
                                                                    <div class="form-group col-xs-4 mt-12">
                                                                        <label>نام آپشن:</label>
                                                                        <input type="text"
                                                                               name="option_group[<?= $k; ?>][name][]"
                                                                               class="form-control p-item-input"
                                                                               value="<?= set_value($v ?? ''); ?>">
                                                                    </div>
                                                                    <div class="form-group col-xs-4 mt-12">
                                                                        <label>توضیحات:</label>
                                                                        <input type="text"
                                                                               name="option_group[<?= $k; ?>][desc][]"
                                                                               class="form-control p-item-input"
                                                                               value="<?= set_value($val['desc'][$k2] ?? ''); ?>">
                                                                    </div>
                                                                    <div class="form-group col-xs-4 mt-12">
                                                                        <label>هزینه آپشن:</label>
                                                                        <input type="text"
                                                                               name="option_group[<?= $k; ?>][price][]"
                                                                               class="form-control p-item-input"
                                                                               value="<?= set_value($val['price'][$k2] ?? ''); ?>">
                                                                    </div>

                                                                    <div class="property-operation-container">
                                                                        <?php if ($k2 == 0): ?>
                                                                            <a href="javascript:void(0);"
                                                                               title="آپشن جدید"
                                                                               class="btn bg-blue btn-icon shadow-depth1 property-operation-add">
                                                                                <i class="icon-plus2"
                                                                                   aria-hidden="true"></i>
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <a href="javascript:void(0);"
                                                                               title="حذف آپشن"
                                                                               class="btn btn-default btn-icon shadow-depth1 property-operation-delete">
                                                                                <i class="icon-trash"
                                                                                   aria-hidden="true"></i>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>

                                                            <div class="clearfix"></div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="col-md-12 form-group position-relative property-item
                                                        border border-dashed border-default border-radius p-20 mt-10">
                                                        <div class="property-operation-container"
                                                             style="top: -15px; left: -15px;">
                                                            <a href="javascript:void(0);"
                                                               title="افزودن گروه‌بندی"
                                                               class="btn bg-success-400 btn-icon btn-rounded shadow-depth1
                                                                          property-operation-add no-margin">
                                                                <i class="icon-plus2" aria-hidden="true"></i>
                                                            </a>
                                                        </div>

                                                        <div class="row position-relative mb-20">
                                                            <div class="col-xs-6 col-sm-6 pt-20">
                                                                <label>عنوان گروه:</label>
                                                                <input type="text" name="option_group[0][title]"
                                                                       class="form-control p-item-input">
                                                            </div>
                                                            <div class="col-xs-3 col-sm-4">
                                                                <div class="form-check form-check-right">
                                                                    <label class="form-check-label ltr">
                                                                        <span class="rtl text-indigo">
                                                                            چند انتخابی
                                                                        </span>
                                                                        <input type="radio" class="selection-radio-0" checked="checked"
                                                                               name="option_group[0][radio]" value="1">
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-right pt-20">
                                                                    <label class="form-check-label ltr">
                                                                        <span class="rtl text-indigo">
                                                                            تک انتخابی
                                                                        </span>
                                                                        <input type="radio" class="selection-radio-0"
                                                                               name="option_group[0][radio]" value="2">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3 col-sm-2">
                                                                <div class="form-check form-check-right">
                                                                    <label class="form-check-label ltr">
                                                                            <span class="rtl text-indigo">
                                                                                اختیاری
                                                                            </span>
                                                                        <input type="radio" class="action-toggle" checked="checked"
                                                                               name="option_group[0][forced]"
                                                                               data-action-toggle-el=".selection-radio-0"
                                                                               data-action-toggle="true"
                                                                               data-action="enable"
                                                                               value="1">
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-right pt-20">
                                                                    <label class="form-check-label ltr">
                                                                            <span class="rtl text-indigo">
                                                                                اجباری
                                                                            </span>
                                                                        <input type="radio" class="action-toggle"
                                                                               data-action-toggle-el=".selection-radio-0"
                                                                               data-action-toggle="false"
                                                                               data-action="enable"
                                                                               name="option_group[0][forced]"
                                                                               value="2">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row position-relative property-each-item"
                                                             style="padding-left: 40px;">
                                                            <div class="col-xs-12"></div>
                                                            <div class="form-group col-xs-4 mt-12">
                                                                <label>نام آپشن:</label>
                                                                <input type="text" name="option_group[0][name][]"
                                                                       class="form-control p-item-input">
                                                            </div>
                                                            <div class="form-group col-xs-4 mt-12">
                                                                <label>توضیحات:</label>
                                                                <input type="text" name="option_group[0][desc][]"
                                                                       class="form-control p-item-input">
                                                            </div>
                                                            <div class="form-group col-xs-4 mt-12">
                                                                <label>هزینه آپشن:</label>
                                                                <input type="text" name="option_group[0][price][]"
                                                                       class="form-control p-item-input">
                                                            </div>

                                                            <div class="property-operation-container">
                                                                <a href="javascript:void(0);"
                                                                   title="آپشن جدید"
                                                                   class="btn bg-blue btn-icon shadow-depth1 property-operation-add">
                                                                    <i class="icon-plus2"
                                                                       aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="clearfix"></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <button type="submit"
                                                class="col-xs-12 col-sm-6 col-sm-push-6 btn btn-primary submit-button pt-15 pb-15 no-border-radius-left">
                                            افزودن طرح
                                            <i class="icon-arrow-left12 position-right"></i>
                                        </button>
                                        <a href="<?= base_url('admin/managePlan'); ?>"
                                           class="col-xs-12 col-sm-6 col-sm-pull-6 btn btn-default bg-white submit-button pt-15 pb-15 no-border-radius-right">
                                            بازگشت
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
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