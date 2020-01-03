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
                                            <h6 class="panel-title">مشخصات نوشته</h6>
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

                                            <div class="col-lg-12 mb-15">
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
                                                       placeholder="اجباری"
                                                       value="<?= set_value($planVals['title'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <span class="text-danger">*</span>
                                                <label>ظرفیت:</label>
                                                <input name="capacity" type="text" class="form-control"
                                                       placeholder="مثال: ۳۰"
                                                       value="<?= set_value($planVals['capacity'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ شروع طرح:</label>
                                                <input type="hidden" name="start_date" id="altDateFieldStart">
                                                <input type="text" class="form-control range-from"
                                                       placeholder="تاریخ شروع طرح" readonly data-time="true"
                                                       data-alt-field="#altDateFieldStart"
                                                       data-format="YYYY/MM/DD - HH:mm"
                                                       value="<?= set_value($planVals['start_date'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ پایان طرح:</label>
                                                <input type="hidden" name="end_date" id="altDateFieldEnd">
                                                <input type="text" class="form-control range-to"
                                                       placeholder="تاریخ پایان طرح" readonly data-time="true"
                                                       data-alt-field="#altDateFieldEnd"
                                                       data-format="YYYY/MM/DD - HH:mm"
                                                       value="<?= set_value($planVals['end_date'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ شروع ثبت نام طرح:</label>
                                                <input type="hidden" name="active_date" id="altDateFieldActive">
                                                <input type="text" class="form-control range-from"
                                                       placeholder="تاریخ شروع ثبت نام طرح" readonly data-time="true"
                                                       data-alt-field="#altDateFieldActive"
                                                       data-format="YYYY/MM/DD - HH:mm"
                                                       value="<?= set_value($planVals['active_date'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <span class="text-danger">*</span>
                                                <label>تاریخ پایان ثبت نام طرح:</label>
                                                <input type="hidden" name="deactive_date" id="altDateFieldDeactive">
                                                <input type="text" class="form-control range-to"
                                                       placeholder="تاریخ پایان ثبت نام طرح" readonly data-time="true"
                                                       data-alt-field="#altDateFieldDeactive"
                                                       data-format="YYYY/MM/DD - HH:mm"
                                                       value="<?= set_value($planVals['deactive_date'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <span class="text-danger">*</span>
                                                <label>مخاطب طرح:</label>
                                                <input name="audience" type="text"
                                                       class="form-control" placeholder="Press Enter"
                                                       data-role="tagsinput"
                                                       value="<?= set_value($planVals['audience'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <label>کلمات کلیدی:</label>
                                                <input name="keywords" type="text"
                                                       class="form-control" placeholder="Press Enter"
                                                       data-role="tagsinput"
                                                       value="<?= set_value($planVals['keywords'] ?? ''); ?>">
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
                                                        name="place"
                                                        rows="10"><?= set_value($planVals['place'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <span class="text-danger">*</span>
                                                <label>شماره‌های پشتیبانی:</label>
                                                <input name="support_phone" type="text"
                                                       class="form-control" placeholder="Press Enter"
                                                       data-role="tagsinput"
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
                                                        id="cntEditor"
                                                        class="form-control"
                                                        placeholder="سوال"
                                                        name="rules"
                                                        rows="10"><?= set_value($planVals['rules'] ?? ''); ?></textarea>
                                            </div>
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

                                            <?php if (count($errors)): ?>
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
                                                                    <a class="io-image-lbl text-grey-300">
                                                                        انتخاب تصویر 1
                                                                    </a>
                                                                    <a class="io-image-name display-block">
                                                                        <?= basename(set_value($planVals['image'] ?? '')); ?>
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
                                                </div>
                                            </div>

                                            <div class="property-items">
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
                                                        <div class="form-group col-xs-6 col-sm-6 mt-12">
                                                            <label>عنوان گروه:</label>
                                                            <input type="text" name="option_group[]"
                                                                   class="form-control p-item-input">
                                                        </div>
                                                        <div class="form-check col-xs-3 col-sm-3 form-check-right pt-20">
                                                            <label class="form-check-label ltr">
                                                                <span class="rtl text-indigo">
                                                                    چند انتخابی
                                                                </span>
                                                                <input type="radio" class="" checked="checked"
                                                                       name="option_choice_0" value="1">
                                                            </label>
                                                        </div>
                                                        <div class="form-check col-xs-3 col-sm-3 form-check-right pt-20">
                                                            <label class="form-check-label ltr">
                                                                <span class="rtl text-indigo">
                                                                    تک انتخابی
                                                                </span>
                                                                <input type="radio" class=""
                                                                       name="option_choice_0" value="2">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="row position-relative property-each-item"
                                                         style="padding-left: 40px;">
                                                        <div class="col-xs-12"></div>
                                                        <div class="form-group col-xs-4 mt-12">
                                                            <label>نام آپشن:</label>
                                                            <input type="text" name="option_name[]"
                                                                   class="form-control p-item-input">
                                                        </div>
                                                        <div class="form-group col-xs-4 mt-12">
                                                            <label>توضیحات:</label>
                                                            <input type="text" name="option_desc[]"
                                                                   class="form-control p-item-input">
                                                        </div>
                                                        <div class="form-group col-xs-4 mt-12">
                                                            <label>قیمت آپشن:</label>
                                                            <input type="text" name="option_price[]"
                                                                   class="form-control p-item-input">
                                                        </div>

                                                        <div class="property-operation-container">
                                                            <a href="javascript:void(0);"
                                                               title="ویژگی جدید"
                                                               class="btn bg-blue btn-icon shadow-depth1 property-operation-add">
                                                                <i class="icon-plus2"
                                                                   aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                </div>
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