<script>    var options = JSON.parse('<?= json_encode($event['options']); ?>');    var baseEventPrice = '<?= $event['base_price'] ?>';</script><section class="listing-details-wrapper bgimage">    <div class="bg_image_holder">        <img alt=""             src="<?= base_url($setting['pages']['all']['topImage']['image'], '/'); ?>">    </div>    <!-- menu part -->    <?php $this->view('templates/fe/home-menu-part', $data); ?>    <!-- End Menu part -->    <div class="listing-info content_above">        <div class="container">            <div class="row">                <div class="col-lg-6 col-md-7">                    <ul class="list-unstyled">                        <?php                        $audience = explode(',', $event['contact']);                        $audience = array_map(function ($val) {                            return EDU_GRADES[$val];                        }, $audience);                        ?>                        <?php foreach ($audience as $item): ?>                            <li class="d-inline-block my-1 badge badge-warning text-white">                                <span class="atbd_badge atbd_badge_featured">                                    <?= 'ویژه ' . $item; ?>                                </span>                            </li>                        <?php endforeach; ?>                        <li class="d-inline-block my-1 badge badge-danger text-white">                            <span class="atbd_badge atbd_badge_popular">                                <?php if ($event['status'] == PLAN_STATUS_ACTIVATE): ?>                                    در حال ثبت نام                                <?php elseif ($event['status'] == PLAN_STATUS_FULL): ?>                                    ظرفیت تکمیل شده                                <?php elseif ($event['status'] == PLAN_STATUS_CLOSED): ?>                                    بسته شده                                <?php endif; ?>                            </span>                        </li>                    </ul>                    <ul class="list-unstyled listing-info--meta">                        <li>                            <div class="average-ratings">                                <span class="atbd_meta atbd_listing_rating">                                    باقی‌مانده:                                    <?= convertNumbersToPersian((int)convertNumbersToPersian($event['capacity'], true) - (int)convertNumbersToPersian($event['filled'], true)); ?>                                     نفر                                </span>                                <span><!--                                                                    ظرفیت کل:-->                                    <strong class="d-inline-block">                                        <?= '';//convertNumbersToPersian($event['capacity']); ?><!--                                                                         نفر-->                                    </strong>                                </span>                            </div>                        </li>                    </ul>                    <!-- ends: .listing-info-meta -->                    <h1 class="aviny display-1"><?= $event['title']; ?></h1>                </div>                <div class="col-lg-6 col-md-5 d-flex align-items-end justify-content-start justify-content-md-end">                    <div class="atbd_listing_action_area">                        <div class="atbd_action atbd_save mt-2">                            <div class="action_button">                                <a href="#detail" class="atbdp-favourites smooth-scroll"><span class=""></span>جزئیات                                    طرح</a>                            </div>                        </div>                        <div class="atbd_action atbd_save mt-2">                            <div class="action_button">                                <a href="#brochure" class="atbdp-favourites smooth-scroll"><span class=""></span>بروشورها</a>                            </div>                        </div>                        <div class="atbd_action atbd_save mt-2">                            <div class="action_button">                                <a href="#gallery" class="atbdp-favourites smooth-scroll"><span                                            class=""></span>تصاویر</a>                            </div>                        </div>                        <?php if (count($event['videos'])): ?>                            <div class="atbd_action atbd_save mt-2">                                <div class="action_button">                                    <a href="#videos" class="atbdp-favourites smooth-scroll"><span                                                class=""></span>ویدیوها</a>                                </div>                            </div>                        <?php endif; ?>                        <div class="atbd_action atbd_save mt-2">                            <div class="action_button">                                <a href="#register" class="atbdp-favourites smooth-scroll"><span class=""></span>انتخاب                                    طرح و ثبت نام</a>                            </div>                        </div>                    </div><!-- ends: .atbd_listing_action_area -->                </div>            </div>        </div>    </div><!-- ends: .listing-info --></section><!-- ends: .card-details-wrapper --><div class="modal fade" id="rule_modal" tabindex="-1" role="dialog" aria-labelledby="rule_modal_label"     aria-hidden="true">    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">        <div class="modal-content">            <div class="modal-header">                <h5 class="modal-title iranyekan-regular" id="claim_listing_label">                    <i class="la la-check-square"></i>                    قوانین                </h5>                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span                            aria-hidden="true">&times;</span></button>            </div>            <div class="modal-body">                <?= $event['rules']; ?>            </div>        </div>    </div></div><!-- Modal --><?php if (isset($eventSubmitErrors) && count($eventSubmitErrors)): ?>    <div class="modal fade omega-modal-show" id="eventMsg" tabindex="-1" role="dialog"         aria-hidden="true">        <div class="modal-dialog modal-dialog-centered" role="document">            <div class="modal-content">                <div class="modal-body text-center p-top-40 p-bottom-50">                    <span class="font-size-huge la la-times-circle-o color-danger"></span>                    <h1 class="display-3 m-bottom-10 iranyekan-regular">توجه</h1>                    <div class="m-bottom-30">                        <?php foreach ($eventSubmitErrors ?? [] as $error): ?>                            <p class="my-1">                                <?= $error; ?>                            </p>                        <?php endforeach; ?>                    </div>                    <div class="d-flex justify-content-center">                        <button type="button"                                class="btn btn-secondary m-right-15"                                data-dismiss="modal">                            باشه                        </button>                    </div>                </div>            </div>        </div>    </div><?php endif; ?><!-- End message modal --><section class="directory_listiing_detail_area single_area section-bg section-padding-strict">    <div class="container">        <div class="row">            <div class="col-lg-8">                <div class="alert alert-warning">                    <p class="m-0 iranyekan-regular">                        <strong>                            توجه:                        </strong>                        ثبت طرح به منزله اتمام عملیات ثبت نام در طرح                        <strong>نیست! </strong>                        و باید حتما پیش پرداخت را انجام دهید.                    </p>                </div>                <div class="atbd_content_module atbd_listing_gallery">                    <div class="atbd_content_module__tittle_area">                        <div class="atbd_area_title">                            <h4 class="aviny" id="gallery"><span class="la la-image"></span>تصاویر</h4>                        </div>                    </div>                    <div class="atbdb_content_module_contents ltr px-0 pt-0">                        <div class="gallery-wrapper">                            <div class="gallery-images">                                <?php foreach ($event['gallery'] as $gallery): ?>                                    <div class="single-image">                                        <img src="<?= base_url($gallery['image']); ?>" alt="">                                    </div>                                <?php endforeach; ?>                            </div><!-- ends: .gallery-images -->                            <div class="gallery-thumbs">                                <?php foreach ($event['gallery'] as $gallery): ?>                                    <div class="single-thumb">                                        <img src="<?= base_url($gallery['image']); ?>" alt="">                                    </div>                                <?php endforeach; ?>                            </div><!-- ends: .gallery-thumbs -->                        </div><!-- ends: .gallery-wrapper -->                    </div>                </div><!-- ends: .atbd_content_module -->                <div class="atbd_content_module atbd_listing_details">                    <div class="atbd_content_module__tittle_area">                        <div class="atbd_area_title">                            <h4 class="aviny" id="detail"><span class="la la-file-text-o"></span>                                جزئیات طرح                            </h4>                        </div>                    </div>                    <div class="atbdb_content_module_contents iranyekan-light text-justify">                        <p>                            <?= $event['description']; ?>                        </p>                    </div>                </div><!-- ends: .atbd_content_module -->                <?php if (count($event['videos'])): ?>                    <div class="atbd_content_module atbd_faqs_module">                        <div class="atbd_content_module__tittle_area">                            <div class="atbd_area_title">                                <h4 id="videos" class="aviny"><span class="la la-video-camera"></span>                                    ویدیوها                                </h4>                            </div>                        </div>                        <div class="atbdb_content_module_contents ltr p-0">                            <div class="gallery-wrapper">                                <div class="gallery-images">                                    <?php foreach ($event['videos'] as $gallery): ?>                                        <div class="single-image">                                            <video readonly controls class="d-block w-100">                                                <source src="<?= base_url($gallery['video']); ?>">                                            </video>                                        </div>                                    <?php endforeach; ?>                                </div><!-- ends: .gallery-videos -->                            </div><!-- ends: .gallery-wrapper -->                        </div>                    </div><!-- ends: .atbd_content_module -->                <?php endif; ?>                <div class="atbd_content_module atbd_faqs_module">                    <div class="atbd_content_module__tittle_area">                        <div class="atbd_area_title">                            <h4 class="aviny" id="register"><span class="la la-table"></span>                                انتخاب آیتم و خرید                            </h4>                        </div>                    </div>                    <?php if (!empty($event['base_price'])): ?>                        <div class="alert bg-secondary text-white d-flex rounded-0 m-0">                            <label class="col">                                هزینه ورودی:                            </label>                            <span class="font-size-md iranyekan-regular">                                <?= convertNumbersToPersian(number_format(convertNumbersToPersian($event['base_price'], true))); ?>                                تومان                            </span>                        </div>                    <?php endif; ?>                    <div class="atbdb_content_module_contents">                        <div class="checkout-form">                            <form action="<?= base_url('event/detail/' . $param[0]); ?>" method="post">                                <?= $form_token_save_event ?? ''; ?>                                <?php foreach ($event['options'] as $k => $option): ?>                                    <div class="p-3 bg-dark">                                        <h4 class="iranyekan-regular text-white">                                            <?= $option['title']; ?>                                        </h4>                                    </div>                                    <?php                                    $isRadio = $option['radio'] == 2 ? true : false;                                    $isForced = $option['forced'] == 2 ? true : false;                                    ?>                                    <div class="checkout-table table-responsive mb-4">                                        <table id="directorist-checkout-table<?= ($k + 1); ?>"                                               class="table table-bordered <?= $isRadio && !$isForced ? 'table-single-select' : 'table-multi-select'; ?>">                                            <thead>                                            <tr>                                                <th colspan="2">انتخاب طرح</th>                                                <th>هزینه</th>                                            </tr>                                            </thead>                                            <tbody>                                            <?php foreach ($option['name'] as $k2 => $name): ?>                                                <tr>                                                    <td width="100px">                                                        <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary custom-control-inline">                                                            <input type="<?= $isRadio && !$isForced ? 'radio' : 'checkbox'; ?>"                                                                <?php if (!$isForced): ?> name="select-chk-<?= ($k + 1); ?><?= $isRadio ? '' : '[]'; ?>" <?php endif; ?>                                                                   class="custom-control-input <?= $isRadio && !$isForced ? 'event-radio-input' : 'event-checkbox-input'; ?>"                                                                   id="select-chk-<?= ($k + 1) . '-' . ($k2 + 1); ?>"                                                                   value="<?= $k2; ?>"                                                                   data-option-idx="<?= $k . '-' . $k2 ?>"                                                                <?= $isForced ? 'data-is-forced="forced"' : ''; ?>                                                                <?= $isForced ? 'readonly="readonly"' : ''; ?>                                                                <?= $isForced ? 'disabled="disabled"' : ''; ?>                                                                <?= $isForced ? 'checked="checked"' : set_value($_POST['select-chk-' . ($k + 1)] ?? '', $k2, 'checked="checked"', '', '==') ?>>                                                            <label class="custom-control-label"                                                                   for="select-chk-<?= ($k + 1) . '-' . ($k2 + 1); ?>"></label>                                                        </div>                                                    </td>                                                    <td>                                                        <h4 class="aviny"><?= $name; ?></h4>                                                        <?php if (!empty($option['desc'][$k2])): ?>                                                            <p class="iranyekan-light">                                                                <?= $option['desc'][$k2]; ?>                                                            </p>                                                        <?php endif; ?>                                                    </td>                                                    <td>                                                        <?php if (is_numeric($option['price'][$k2])): ?>                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($option['price'][$k2], true))); ?>                                                            تومان                                                        <?php else: ?>                                                            <?= $option['price'][$k2]; ?>                                                        <?php endif; ?>                                                    </td>                                                </tr>                                            <?php endforeach; ?>                                            </tbody>                                        </table>                                    </div><!-- ends: .checkout-table -->                                <?php endforeach; ?>                                <div class="alert alert-info d-flex">                                    <label class="col">هزینه ورودی طرح و آیتم‌های انتخاب شده:</label>                                    <strong>                                        <span class="font-size-md" id="selected-items-price">                                            0                                        </span>                                        تومان                                    </strong>                                </div>                                <hr class="my-4">                                <div class="text-center">                                    <div class="d-flex">                                        <?php if ($event['status'] == PLAN_STATUS_ACTIVATE): ?>                                            <?php if ($auth->isLoggedIn()): ?>                                                <?php if ((int)$event['capacity'] > (int)$event['filled']): ?>                                                    <div class="col pt-3 custom-control custom-checkbox checkbox-outline checkbox-outline-primary custom-control-inline">                                                        <input type="checkbox" name="rule_agree"                                                               class="custom-control-input"                                                               id="ruleAgree">                                                        <label class="custom-control-label" for="ruleAgree">                                                            <a href="javascript:void(0);"                                                               data-toggle="modal"                                                               data-target="#rule_modal">                                                                قوانین و مقررات                                                            </a>                                                            را مطالعه کرده و با آنها موافقم.                                                        </label>                                                    </div>                                                    <button type="submit" class="btn btn-primary" name="save-event-btn">                                                        ثبت نام در طرح                                                    </button>                                                <?php else: ?>                                                    <span class="badge badge-danger py-1 col-12">                                                        ظرفیت تکمیل شده                                                    </span>                                                <?php endif; ?>                                            <?php else: ?>                                                <p class="m-0 pt-3">                                                    برای ثبت نام در این طرح ابتدا به سایت                                                    <a data-toggle="modal"                                                       data-target="#login_modal"                                                       href="javascript:void(0);">                                                        وارد شوید                                                    </a>                                                    و یا                                                    <a data-toggle="modal"                                                       data-target="#signup_modal"                                                       href="javascript:void(0);">                                                        ثبت نام کنید.                                                    </a>                                                </p>                                            <?php endif; ?>                                        <?php else: ?>                                            <span class="badge badge-danger py-1 col-12">                                                <?php if ($event['status'] == PLAN_STATUS_FULL): ?>                                                    ظرفیت تکمیل شده                                                <?php elseif ($event['status'] == PLAN_STATUS_IN_PROGRESS): ?>                                                    در حال برگزاری                                                <?php elseif ($event['status'] == PLAN_STATUS_CLOSED): ?>                                                    بسته شده                                                <?php else: ?>                                                    امکان ثبت نام وجود ندارد                                                <?php endif; ?>                                            </span>                                        <?php endif; ?>                                    </div>                                </div>                            </form>                        </div><!-- ends: .checkout-form -->                    </div>                </div><!-- ends: .atbd_content_module -->            </div>            <div class="col-lg-4 mt-5 mt-lg-0">                <?php if (count($event['brochure'])): ?>                    <div class="widget atbd_widget widget-card">                        <div class="atbd_widget_title">                            <h4 class="aviny" id="brochure"><span class="la la-image"></span>                                بروشورها                            </h4>                        </div><!-- ends: .atbd_widget_title -->                        <div class="widget-body atbd_author_info_widget ltr">                            <div class="gallery-wrapper">                                <div class="gallery-brochure">                                    <?php foreach ($event['brochure'] as $gallery): ?>                                        <div class="single-image">                                            <img src="<?= base_url($gallery['image']); ?>" alt="">                                        </div>                                    <?php endforeach; ?>                                </div><!-- ends: .gallery-videos -->                            </div><!-- ends: .gallery-wrapper -->                        </div><!-- ends: .widget-body -->                    </div><!-- ends: .widget -->                <?php endif; ?>                <div class="widget atbd_widget widget-card">                    <div class="atbd_widget_title">                        <h4 class="aviny"><span class="la la-info-circle"></span>                            هزینه ثبت نام                        </h4>                    </div><!-- ends: .atbd_widget_title -->                    <div class="widget-body atbd_author_info_widget">                        <div class="atbd_widget_contact_info">                            <ul>                                <li>                                    <span class="la la-money"></span>                                    <span class="atbd_info iranyekan-light">                                        <label class="font-size-sm"><strong>هزینه طرح: </strong></label>                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($event['total_price'], true))); ?>                                        تومان                                    </span>                                </li>                                <li>                                    <span class="la la-money"></span>                                    <span class="atbd_info iranyekan-light">                                        <label class="font-size-sm"><strong>هزینه پیش‌پرداخت: </strong></label>                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($event['min_price'], true))); ?>                                        تومان                                    </span>                                </li>                            </ul>                        </div><!-- ends: .atbd_widget_contact_info -->                    </div><!-- ends: .widget-body -->                </div><!-- ends: .widget -->                <div class="widget atbd_widget widget-card">                    <div class="atbd_widget_title">                        <h4 class="aviny"><span class="la la-calendar"></span>                            تاریخ برگزاری                        </h4>                    </div><!-- ends: .atbd_widget_title -->                    <div class="widget-body atbd_author_info_widget">                        <div class="atbd_widget_contact_info">                            <ul>                                <li>                                    <span class="la la-calendar-check-o"></span>                                    <span class="atbd_info iranyekan-light">                                        <label class="font-size-sm"><strong>شروع ثبت نام: </strong></label>                                        <time datetime="<?= date('Y-m-d H:i', $event['active_at']); ?>"                                              class="atbd_info iranyekan-light">                                            <?= jDateTime::date('j F Y', $event['active_at']); ?>                                        </time>                                    </span>                                </li>                                <li>                                    <span class="la la-calendar-check-o"></span>                                    <span class="atbd_info iranyekan-light">                                        <label class="font-size-sm"><strong>پایان ثبت نام: </strong></label>                                        <time datetime="<?= date('Y-m-d H:i', $event['deactive_at']); ?>"                                              class="atbd_info iranyekan-light">                                            <?= jDateTime::date('j F Y', $event['deactive_at']); ?>                                        </time>                                    </span>                                </li>                                <li>                                    <span class="la la-calendar-check-o"></span>                                    <span class="atbd_info iranyekan-light">                                        <label class="font-size-sm"><strong>تاریخ برگزاری: </strong></label>                                        <time datetime="<?= date('Y-m-d H:i', $event['start_at']); ?>"                                              class="atbd_info iranyekan-light">                                            <?= jDateTime::date('j F Y', $event['start_at']); ?>                                        </time>                                    </span>                                </li>                                <li>                                    <span class="la la-calendar-check-o"></span>                                    <span class="atbd_info iranyekan-light">                                        <label class="font-size-sm"><strong>تاریخ پایان: </strong></label>                                        <time datetime="<?= date('Y-m-d H:i', $event['end_at']); ?>"                                              class="atbd_info iranyekan-light">                                            <?= jDateTime::date('j F Y', $event['end_at']); ?>                                        </time>                                    </span>                                </li>                            </ul>                        </div><!-- ends: .atbd_widget_contact_info -->                    </div><!-- ends: .widget-body -->                </div><!-- ends: .widget -->                <div class="widget atbd_widget widget-card">                    <div class="atbd_widget_title">                        <h4 class="aviny"><span class="la la-phone"></span>                            اطلاعات تماس و پشتیبانی                        </h4>                    </div><!-- ends: .atbd_widget_title -->                    <div class="widget-body atbd_author_info_widget">                        <div class="atbd_widget_contact_info">                            <ul>                                <li>                                    <span class="la la-map-marker"></span>                                    <span class="atbd_info iranyekan-light">                                        <label class="font-size-sm"><strong>محل برگزاری: </strong></label>                                        <?= $event['place']; ?>                                    </span>                                </li>                                <li>                                    <span class="la la-map-marker"></span>                                    <span class="atbd_info iranyekan-light">                                        <label class="font-size-sm"><strong>محل پشتیبانی: </strong></label>                                        <?= $event['support_place'] ?? '<i class="la la-minus"></i>'; ?>                                    </span>                                </li>                                <li class="dropdown-divider my-4"></li>                                <?php                                $supportPhones = explode(',', $event['support_phone']);                                ?>                                <?php foreach ($supportPhones as $phone): ?>                                    <li>                                        <span class="la la-phone"></span>                                        <span class="atbd_info iranyekan-light">                                            <label class="font-size-sm"><strong>تلفن پشتیبانی: </strong></label>                                            <?= convertNumbersToPersian($phone); ?>                                        </span>                                    </li>                                <?php endforeach; ?>                            </ul>                        </div><!-- ends: .atbd_widget_contact_info -->                    </div><!-- ends: .widget-body -->                </div><!-- ends: .widget -->                <?php if (count($relatedEvents)): ?>                    <div class="widget atbd_widget widget-card">                        <div class="atbd_widget_title">                            <h4 class="aviny"><span class="la la-list-alt"></span>طرح‌های مشابه</h4>                            <a href="<?= base_url('event/events'); ?>" class="font-size-sm">مشاهده همه</a>                        </div><!-- ends: .atbd_widget_title -->                        <div class="atbd_categorized_listings atbd_similar_listings">                            <ul class="listings">                                <?php foreach ($relatedEvents as $evt): ?>                                    <li>                                        <div class="atbd_left_img">                                            <a href="<?= base_url('event/detail/' . $evt['slug']); ?>">                                                <img style="width: 90px;"                                                     src="<?= base_url($evt['image']); ?>"                                                     alt="<?= $evt['title']; ?>">                                            </a>                                        </div>                                        <div class="atbd_right_content">                                            <div class="cate_title">                                                <h4 class="font-size-md">                                                    <a href="<?= base_url('event/detail/' . $evt['slug']); ?>">                                                        <?= $evt['title']; ?>                                                    </a>                                                </h4>                                            </div>                                            <p class="listing_value">                                                <span>                                                    <?= convertNumbersToPersian(number_format(convertNumbersToPersian($evt['total_price'], true))); ?>                                                    تومان                                                </span>                                            </p>                                        </div>                                    </li>                                <?php endforeach; ?>                            </ul>                        </div> <!-- ends .atbd_similar_listings -->                    </div><!-- ends: .widget -->                <?php endif; ?>            </div>        </div>    </div></section><!-- ends: .directory_listiing_detail_area --><?php if (!$auth->isLoggedIn()): ?>    <?php $this->view('templates/fe/login-modal', $data); ?>    <?php $this->view('templates/fe/signup-modal', $data); ?><?php endif; ?><?php $this->view('templates/fe/home-footer-part', $data); ?>