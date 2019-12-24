<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!-- Main navbar -->
<?php $this->view("templates/be/mainnavbar", $data); ?>
<!-- /main navbar -->
<!-- Page container -->
<script>
    (function ($) {
        'use strict';
        $(function () {
            var showColor = function (color) {
                var originalOption = color.element;
                if (!color.id) return color.text;
                var $color = "<span class='display-inline-block img-xxs img-rounded shadow-depth2 pull-right'" +
                    " style='background-color: " + $(originalOption).attr('data-color') + "'></span>" + color.text;

                return $color;
            };

            $('.my-custom-select').each(function () {
                var $this = $(this);
                if ($this.data('select2')) $this.select2("destroy");
                $this.select2({
                    containerCssClass: 'rtl',
                    dropdownCssClass: 'rtl',
                    templateResult: showColor,
                    templateSelection: showColor,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });
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
                            <i class="icon-circle position-left"></i> <span class="text-semibold">افزودن محصول</span>
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
                            <a href="<?= base_url(); ?>admin/manageProduct">
                                محصولات
                            </a>
                        </li>
                        <li class="active">افزودن محصول</li>
                    </ul>

                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?= base_url(); ?>admin/addProduct" method="post">
                            <?= $data['form_token']; ?>

                            <div class="row">
                                <div class="col-lg-12">
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
                                </div>

                                <div class="col-lg-6">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">مشخصات محصول</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-lg-12 mb-15">
                                                <div class="cursor-pointer pick-file border border-lg border-default"
                                                     data-toggle="modal"
                                                     data-target="#modal_full"
                                                     style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                    <input class="image-file" type="hidden"
                                                           name="image"
                                                           value="<?= set_value($pVals['image'] ?? ''); ?>">
                                                    <div class="media stack-media-on-mobile">
                                                        <div class="media-left">
                                                            <div class="thumb">
                                                                <a class="display-inline-block"
                                                                   style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                    <img
                                                                            src="<?= set_value($pVals['image'] ?? '', '', base_url($pVals['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
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
                                                                    <?= basename(set_value($pVals['image'] ?? '')); ?>
                                                                </a>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12 col-md-6 col-sm-12">
                                                <span class="text-danger">*</span>
                                                <label>عنوان محصول:</label>
                                                <input name="title" type="text" class="form-control"
                                                       placeholder="اجباری"
                                                       value="<?= set_value($pVals['title'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-12 col-md-6 col-sm-12">
                                                <label>عنوان لاتین محصول:</label>
                                                <input name="latin" type="text" class="form-control"
                                                       placeholder="اجباری"
                                                       value="<?= set_value($pVals['latin'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                                <span class="text-danger">*</span>
                                                <label>برند محصول:</label>
                                                <select class="select" name="brand">
                                                    <?php foreach ($brands as $key => $brand): ?>
                                                        <option value="<?= $brand['id']; ?>"
                                                            <?= set_value($pVals['brand'] ?? '', $brand['id'], 'selected', '', '=='); ?>>
                                                            <?= $brand['brand_name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                                <span class="text-danger">*</span>
                                                <label>دسته‌بندی محصول:</label>
                                                <select class="select-rtl" name="category">
                                                    <?php foreach ($categories as $key => $category): ?>
                                                        <option value="<?= $category['id']; ?>"
                                                            <?= set_value($pVals['category'] ?? '', $category['id'], 'selected', '', '=='); ?>>
                                                            <?php for ($i = 1; $i < $category['level']; $i++): ?>
                                                                -
                                                            <?php endfor; ?>
                                                            <?= $category['category_name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-8 col-md-8 col-sm-7">
                                                <label>گارانتی:</label>
                                                <input name="guarantee" type="text" class="form-control"
                                                       placeholder="مانند: گارانتی ۱۸ ماهه سازگار"
                                                       value="<?= set_value($pVals['guarantee'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-4 col-md-4 col-sm-5">
                                                <label>قیمت گارانتی:</label>
                                                <input name="guarantee-price" type="text" class="form-control"
                                                       placeholder="به تومان"
                                                       value="<?= set_value($pVals['guarantee-price'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-8 col-md-8 col-sm-7">
                                                <label>مقدار تخفیف:</label>
                                                <input name="discount" type="text" class="form-control"
                                                       placeholder="عدد (توجه به واحد)"
                                                       value="<?= set_value($pVals['discount'] ?? ''); ?>">
                                            </div>
                                            <div class="form-group col-lg-4 col-md-4 col-sm-5">
                                                <label>واحد تخفیف:</label>
                                                <select class="select-no-search" name="discount-unit">
                                                    <option value="1"
                                                        <?= set_value($pVals['discount-unit'] ?? '', 1, 'selected', '', '=='); ?>>
                                                        تومان
                                                    </option>
                                                    <option value="2"
                                                        <?= set_value($pVals['discount-unit'] ?? '', 2, 'selected', '', '=='); ?>>
                                                        درصد
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                <label>کلمات کلیدی:</label>
                                                <input name="keywords" type="text"
                                                       class="form-control" placeholder="Press Enter"
                                                       data-role="tagsinput"
                                                       value="<?= set_value($pVals['keywords'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="panel panel-white">
                                        <div class="panel-body">
                                            <div class="col-lg-12 text-right">
                                                <label for="pStatus" class="pull-left">
                                                    <span class="text-danger">*</span>
                                                    وضعیت انتشار:
                                                </label>
                                                <input type="checkbox" name="status" id="pStatus"
                                                       class="switchery" <?= set_value($pVals['status'] ?? '', 'off', '', 'checked', '=='); ?> />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">رنگ‌ها</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-lg-12 text-left">
                                                <div class="display-inline-block alert alert-primary
                                                no-border-right no-border-top no-border-bottom border-lg p-10 no-margin-bottom"
                                                     style="width: calc(100% - 50px);">
                                                    انتخاب حداقل یک رنگ اجباری است
                                                </div>
                                                <a href="javascript:void(0);"
                                                   class="btn btn-primary btn-icon add-color ml-5"
                                                   title="اضافه کردن رنگ جدید" data-popup="tooltip">
                                                    <i class="icon-plus2" aria-hidden="true"></i>
                                                </a>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="form-group col-lg-12" id="color-items">
                                                <?php if (count($errors)): ?>
                                                    <?php for ($key = 0; $key < $color_iteration; $key++): ?>
                                                        <div class="row color-item mb-20 position-relative pt-20 <?= $key != 0 ? 'border-top border-grey-300 border-top-dashed' : ''; ?>">
                                                            <div class="col-sm-5">
                                                                <label>رنگ:</label>
                                                                <select class="my-custom-select color-item-select"
                                                                        name="color[color][]">
                                                                    <?php foreach ($colors as $color): ?>
                                                                        <option value="<?= $color['id']; ?>"
                                                                                data-color="<?= $color['color_hex']; ?>"
                                                                            <?= set_value($pVals['color']['color'][$key] ?? '', $color['id'], 'selected', '', '=='); ?>>
                                                                            <?= $color['color_name']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label>تعداد:</label>
                                                                <input name="color[count][]" type="text"
                                                                       class="form-control color-item-input"
                                                                       placeholder="عدد"
                                                                       value="<?= set_value($pVals['color']['count'][$key] ?? ''); ?>">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label>قیمت:</label>
                                                                <input name="color[price][]" type="text"
                                                                       class="form-control color-item-input"
                                                                       placeholder="به تومان"
                                                                       value="<?= set_value($pVals['color']['price'][$key] ?? ''); ?>">
                                                            </div>
                                                            <div class="color-item-clear">
                                                                <?php if ($key != 0): ?>
                                                                    <small class="delete-new-color btn btn-danger"
                                                                           title="حذف">&times;
                                                                    </small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endfor; ?>
                                                <?php else: ?>
                                                    <div class="row color-item mb-20 position-relative pt-20">
                                                        <div class="col-sm-5">
                                                            <label>رنگ:</label>
                                                            <select class="my-custom-select color-item-select"
                                                                    name="color[color][]">
                                                                <?php foreach ($colors as $color): ?>
                                                                    <option value="<?= $color['id']; ?>"
                                                                            data-color="<?= $color['color_hex']; ?>"
                                                                        <?= set_value($pVals['color'] ?? '', $color['id'], 'selected', '', '=='); ?>>
                                                                        <?= $color['color_name']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label>تعداد:</label>
                                                            <input name="color[count][]" type="text"
                                                                   class="form-control color-item-input"
                                                                   placeholder="عدد">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>قیمت:</label>
                                                            <input name="color[price][]" type="text"
                                                                   class="form-control color-item-input"
                                                                   placeholder="به تومان">
                                                        </div>
                                                        <div class="color-item-clear"></div>
                                                    </div>
                                                <?php endif; ?>
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
                                                <?php foreach ($pVals['imageGallery'] as $key => $img): ?>
                                                    <div class="col-lg-12 col-md-6 col-sm-12 mb-15 slide-item">
                                                        <div class="cursor-pointer pick-file border border-lg border-default"
                                                             data-toggle="modal"
                                                             data-target="#modal_full"
                                                             style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                            <input class="image-file" type="hidden"
                                                                   name="imageGallery[]"
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
                                                <div class="col-lg-12 col-md-6 col-sm-12 mb-15 slide-item">
                                                    <div class="cursor-pointer pick-file border border-lg border-default"
                                                         data-toggle="modal"
                                                         data-target="#modal_full"
                                                         style="border-style: dashed; padding: 0 10px 10px 0; box-sizing: border-box;">
                                                        <input class="image-file" type="hidden"
                                                               name="imageGallery[]"
                                                               value="<?= set_value($pVals['image'] ?? ''); ?>">
                                                        <div class="media stack-media-on-mobile">
                                                            <div class="media-left">
                                                                <div class="thumb">
                                                                    <a class="display-inline-block"
                                                                       style="-webkit-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);-moz-box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.4);">
                                                                        <img
                                                                                src="<?= set_value($pVals['image'] ?? '', '', base_url($pVals['image'] ?? ''), asset_url('be/images/placeholder.jpg')); ?>"
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
                                                                        <?= basename(set_value($pVals['image'] ?? '')); ?>
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
                                </div>

                                <div class="col-lg-12">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">ویژگی‌های کلی محصول (برای دید کلی محصول)</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <input name="p-keywords" type="text"
                                                       class="form-control" placeholder="Press Enter"
                                                       data-role="tagsinput"
                                                       value="<?= set_value($pVals['p-keywords'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">محصولات مرتبط</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <select class="select-rtl" multiple="multiple"
                                                        name="related[]" data-placeholder="انتخاب کنید">
                                                    <?php foreach ($products as $product): ?>
                                                        <option value="<?= $product['id']; ?>"
                                                            <?= in_array($product['id'], $pVals['related'] ?? []) ? 'selected' : ''; ?>>
                                                            <?= $product['product_title']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">توضیحات محصول</h6>
                                            <div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group col-md-12 mt-10">
                                                <textarea
                                                        class="form-control"
                                                        style="width: 100%; min-width: 100%; max-width: 100%; min-height: 300px;"
                                                        name="description" placeholder="متن توضیحات محصول"
                                                        rows="10"><?= set_value($pVals['description'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">ویژگی‌های محصول</h6>
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
                                                        همچنین برای افزودن ویژگی جدید، دکمه
                                                        <span class="text-blue">
                                                            ویژگی جدید
                                                        </span>
                                                        در هر زیر ویژگی را فشار دهید.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="property-items">
                                                <?php if (count($errors)): ?>
                                                    <?php foreach ($pVals['title-property'] as $k => $val): ?>
                                                        <div class="col-md-12 form-group position-relative property-item
                                                            border border-dashed border-default border-radius p-20 mt-10"
                                                             style="width: calc(100% - 15px);">
                                                            <div class="property-operation-container"
                                                                 style="top: -15px; left: -15px;">
                                                                <?php if ($k == 0): ?>
                                                                    <a href="javascript:void(0);"
                                                                       title="افزودن گروه‌بندی"
                                                                       class="btn btn-success btn-icon btn-rounded shadow-depth4
                                                                          property-operation-add no-margin">
                                                                        <i class="icon-plus2" aria-hidden="true"></i>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <a href="javascript:void(0);" title="حذف گروه‌بندی"
                                                                       class="btn btn-danger btn-icon btn-rounded shadow-depth4
                                                                          property-operation-delete no-margin">
                                                                        <i class="icon-trash" aria-hidden="true"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>

                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group col-md-12">
                                                                    <label>انتخاب عنوان ویژگی:</label>
                                                                    <select class="select p-item-select"
                                                                            name="title-property[]">
                                                                        <option value="0" selected>
                                                                            انتخاب کنید
                                                                        </option>
                                                                        <?php foreach ($titles as $key => $title): ?>
                                                                            <option value="<?= $title['title_name']; ?>"
                                                                                <?= set_value($val['title'] ?? '', $title['title_name'], 'selected', '', '=='); ?>>
                                                                                <?= $title['title_name']; ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <?php foreach ($val['property'] as $k2 => $prop): ?>
                                                                <div class="col-md-12 position-relative property-each-item"
                                                                     style="padding-left: 40px;">
                                                                    <div class="form-group col-md-4">
                                                                        <label>انتخاب ویژگی:</label>
                                                                        <select class="select p-item-select"
                                                                                name="title-property[][]">
                                                                            <option value="0" selected>
                                                                                انتخاب کنید
                                                                            </option>
                                                                            <?php foreach ($properties as $key => $property): ?>
                                                                                <option value="<?= $property['property_name']; ?>"
                                                                                    <?= set_value($prop ?? '', $property['property_name'], 'selected', '', '=='); ?>>
                                                                                    <?= $property['property_name']; ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-8">
                                                                        <label>متن ویژگی:</label>
                                                                        <input name="title-property[][][]" type="text"
                                                                               class="form-control p-item-input"
                                                                               placeholder="Press Enter"
                                                                               data-role="tagsinput"
                                                                               value="<?= set_value($val['value'][$k2] ?? ''); ?>">
                                                                    </div>

                                                                    <div class="property-operation-container">
                                                                        <?php if ($k2 == 0): ?>
                                                                            <a href="javascript:void(0);"
                                                                               title="ویژگی جدید"
                                                                               class="btn btn-default btn-icon shadow-depth1 property-operation-add">
                                                                                <i class="icon-plus2"
                                                                                   aria-hidden="true"></i>
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <a href="javascript:void(0);"
                                                                               title="حذف ویژگی"
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
                                                            border border-dashed border-default border-radius p-20 mt-10"
                                                         style="width: calc(100% - 15px);">
                                                        <div class="property-operation-container"
                                                             style="top: -15px; left: -15px;">
                                                            <a href="javascript:void(0);" title="افزودن گروه‌بندی"
                                                               class="btn btn-success btn-icon btn-rounded shadow-depth4
                                                           property-operation-add no-margin">
                                                                <i class="icon-plus2" aria-hidden="true"></i>
                                                            </a>
                                                        </div>

                                                        <div class="col-md-12 form-group">
                                                            <div class="form-group col-md-12">
                                                                <label>انتخاب عنوان ویژگی:</label>
                                                                <select class="select p-item-select"
                                                                        name="title-property[]">
                                                                    <option value="0" selected>
                                                                        انتخاب کنید
                                                                    </option>
                                                                    <?php foreach ($titles as $key => $title): ?>
                                                                        <option value="<?= $title['title_name']; ?>">
                                                                            <?= $title['title_name']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 position-relative property-each-item"
                                                             style="padding-left: 40px;">
                                                            <div class="form-group col-md-4">
                                                                <label>انتخاب ویژگی:</label>
                                                                <select class="select p-item-select"
                                                                        name="title-property[][]">
                                                                    <option value="0" selected>
                                                                        انتخاب کنید
                                                                    </option>
                                                                    <?php foreach ($properties as $key => $property): ?>
                                                                        <option value="<?= $property['property_name']; ?>">
                                                                            <?= $property['property_name']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <label>متن ویژگی:</label>
                                                                <input name="title-property[][][]" type="text"
                                                                       class="form-control p-item-input"
                                                                       placeholder="Press Enter" data-role="tagsinput"
                                                                       value="">
                                                            </div>

                                                            <div class="property-operation-container">
                                                                <a href="javascript:void(0);" title="ویژگی جدید"
                                                                   class="btn btn-default btn-icon shadow-depth1 property-operation-add">
                                                                    <i class="icon-plus2" aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6 col-lg-push-4 col-sm-push-3">
                                    <button type="submit"
                                            class="btn btn-success btn-block submit-button pt-15 pb-15 mb-20">
                                        افزودن محصول
                                        <i class="icon-arrow-left12 position-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Standard width modal -->
                <div id="modal_full" class="modal fade lazyContainer">
                    <div class="modal-dialog modal-full">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title">
                                    انتخاب فایل
                                </h5>
                            </div>

                            <div id="files-body" class="modal-body">
                                <?php $this->view("templates/be/efm-view", $data); ?>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <i class="icon-cancel-circle2 position-left" aria-hidden="true"></i>
                                    لغو
                                </button>
                                <button id="file-ok" type="button" class="btn btn-primary" data-dismiss="modal">
                                    <i class="icon-checkmark-circle position-left" aria-hidden="true"></i>
                                    انتخاب
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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