<section class="header-breadcrumb bgimage overlay overlay--dark">    <div class="bg_image_holder"><img src="img/breadcrumb1.jpg" alt=""></div>    <div class="mainmenu-wrapper">        <div class="menu-area menu1 menu--light">            <div class="top-menu-area">                <div class="container-fluid">                    <div class="row">                        <!--                       menu part-->                        <?php $this->view('templates/fe/home-menu-part'); ?>                        <!--                        End Menu part-->                    </div>                    <!-- end /.row -->                </div>                <!-- end /.container -->            </div>            <!-- end  -->        </div>    </div><!-- ends: .mainmenu-wrapper -->    <div class="breadcrumb-wrapper content_above">        <div class="container">            <div class="row">                <div class="col-lg-12 text-center">                    <h1 class="page-title aviny">داشبورد</h1>                    <nav aria-label="breadcrumb">                        <ol class="breadcrumb">                            <li class="breadcrumb-item"><a href="#">امگا</a></li>                            <li class="breadcrumb-item active iranyekan-light" aria-current="page">حساب کاربری</li>                        </ol>                    </nav>                </div>            </div>        </div>    </div><!-- ends: .breadcrumb-wrapper --></section><section class="dashboard-wrapper section-bg p-bottom-70">    <div class="dashboard-nav">        <div class="container">            <div class="row">                <div class="col-lg-12">                    <div class="dashboard-nav-area">                        <ul class="nav" id="dashboard-tabs" role="tablist">                            <li class="nav-item">                                <a class="nav-link active" id="all-listings" data-toggle="tab" href="#listings"                                   role="tab" aria-controls="listings" aria-selected="true">طرح‌های من</a>                            </li>                            <li class="nav-item">                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"                                   aria-controls="profile" aria-selected="false">اطلاعات من</a>                            </li>                        </ul>                        <div class="nav_button">                            <a href="" class="btn btn-secondary">خروج</a>                        </div>                    </div>                </div><!-- ends: .col-lg-12 -->            </div>        </div>    </div><!-- ends: .dashboard-nav -->    <div class="tab-content p-top-100" id="dashboard-tabs-content">        <div class="tab-pane fade show active" id="listings" role="tabpanel" aria-labelledby="all-listings">            <div class="container">                <div class="row">                    <div class="col-lg-4 col-sm-6">                        <div class="atbd_single_listing atbd_listing_card">                            <article class="atbd_single_listing_wrapper ">                                <figure class="atbd_listing_thumbnail_area">                                    <div class="atbd_listing_image">                                        <a href=""><img src="<?= asset_url('fe/img/p1.jpg') ?>" alt="listing image"></a>                                    </div>                                    <figcaption class="atbd_thumbnail_overlay_content">                                        <div class="atbd_upper_badge">                                            <span class="atbd_badge atbd_badge_new">ویژه پایه دهم</span>                                        </div>                                    </figcaption>                                </figure>                                <div class="atbd_listing_info">                                    <div class="atbd_content_upper">                                        <div class="atbd_dashboard_tittle_metas">                                            <h4 class="atbd_listing_title">                                                <a href="">                                                    طرح پرش امگا                                                </a>                                            </h4>                                        </div><!-- ends: .atbd_dashboard_tittle_metas -->                                        <div class="atbd_card_action">                                            <div class="atbd_listing_meta">                                                <span class="atbd_meta atbd_listing_rating">                                                    پرداخت موفق                                                </span>                                            </div><!-- ends: .atbd listing meta -->                                            <a href="#"                                               class="directory_remove_btn btn btn-sm btn-outline-danger"                                               data-toggle="modal" data-target="#modal-item-remove">                                                جزئیات خرید                                            </a>                                        </div>                                        <!--ends .db_btn_area-->                                    </div>                                </div><!-- end .atbd_content_upper -->                                <div class="atbd_listing_bottom_content">                                    <div class="listing-meta">                                        <p class="iranyekan-light">                                            <span>زمان خرید:</span>                                            ۵ اردیبهشت ۱۳۹۸                                        </p>                                        <p class="iranyekan-light">                                            <span>مبلغ پرداخت شده:</span>                                            ۳۰۰ هزار تومان                                        </p>                                        <p class="iranyekan-light">                                            <span>                                                مبلغ قابل پرداخت:                                            </span>                                            ۲۰۰ هزارتومان                                        </p>                                    </div>                                </div><!-- end .atbd_listing_bottom_content -->                        </div><!-- ends: .atbd_listing_info -->                        </article>                    </div><!-- ends: .atbd_single_listing -->                </div><!-- ends: .col-lg-4 -->            </div>        </div>        <!-- ends: .tab-pane -->        <div class="tab-pane fade p-bottom-30" id="profile" role="tabpanel" aria-labelledby="profile-tab">            <div class="container">                <div class="row">                    <div class="col-lg-3 ">                        <div class="atbd_content_module">                            <div class="atbd_content_module__tittle_area">                                <div class="atbd_area_title">                                    <h4 class="aviny"><span class="la la-calendar-check-o"></span>تصویر شما</h4>                                </div>                            </div>                            <div class="atbdb_content_module_contents">                                <div id="_listing_gallery">                                    <div class="add_listing_form_wrapper" id="gallery_upload">                                        <div class="form-group text-center">                                            <!--  add & remove image links -->                                            <p class="hide-if-no-js">                                                <a href="#" class="upload-header btn btn-outline-secondary">                                                    بارگذاری تصویر                                                </a>                                            </p>                                        </div>                                        <div class="form-group text-center">                                            <!-- image container, which can be manipulated with js -->                                            <div class="listing-img-container">                                                <img src="<?= asset_url('fe/img/picture.png') ?>" alt="تصویر آپلود نشده است.">                                                <p></p>                                            </div>                                            <!--  add & remove image links -->                                            <p class="hide-if-no-js">                                                <a id="delete-custom-img" class="btn btn-outline-danger hidden" href="#">                                                    حذف تصویر                                                </a><br>                                            </p>                                        </div>                                    </div>                                    <!--ends add_listing_form_wrapper-->                                </div>                            </div><!-- ends: .atbdb_content_module_contents -->                        </div><!-- ends: .atbd_content_module -->                    </div><!-- ends: .col-lg-10 -->                    <div class="col-lg-9 ">                        <div class="atbd_content_module">                            <div class="atbd_content_module__tittle_area">                                <div class="atbd_area_title">                                    <h4 class="aviny"><span class="la la-user"></span> اطلاعات کلی</h4>                                </div>                            </div>                            <div class="atbdb_content_module_contents">                                <form action="/">                                    <div class="row">                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="name" class="form-label">                                                نام                                                <span class="iranyekan-light text-danger">                                            (اجباری)                                        </span>                                            </label>                                            <input name="name" type="text" class="form-control" id="name"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="last-name" class="form-label">                                                نام خانوادگی                                                <span class="iranyekan-light text-danger">                                            (اجباری)                                        </span>                                            </label>                                            <input name="last-name" type="text" class="form-control" id="last-name"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="f-name" class="form-label">                                                نام پدر                                            </label>                                            <input name="f-name" type="text" class="form-control" id="f-name"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="n-code" class="form-label">                                                کد ملی                                                <span class="iranyekan-light text-danger">                                            (اجباری)                                        </span>                                            </label>                                            <input name="n-code" type="text" class="form-control" id="n-code"                                                   placeholder="کد ملی 10 رقمی"                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="id-number" class="form-label">                                                شماره شناسنامه                                            </label>                                            <input name="id-number" type="text" class="form-control" id="n-code"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="id-location" class="form-label">                                                محل صدور                                            </label>                                            <input name="id-location" type="text" class="form-control" id="n-code"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="soldiery" class="form-label">                                                وضعیت تحصیلی                                            </label>                                            <div class="select-basic">                                                <select name="soldiery" class="form-control">                                                    <option value="6">پایه ششم</option>                                                    <option value="7">پایه هفتم</option>                                                    <option value="8">پایه هشتم</option>                                                    <option value="9">پایه نهم</option>                                                    <option value="10">پایه دهم</option>                                                    <option value="11">پایه یازدهم</option>                                                    <option value="12">پایه دوازدهم</option>                                                    <option value="13">پشت کنکور</option>                                                    <option value="14">دیپلم</option>                                                    <option value="15">فوق دیپلم</option>                                                    <option value="16">لیسانس</option>                                                    <option value="17">فوق لیسانس</option>                                                    <option value="18">دکترا</option>                                                </select>                                            </div>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <div class="form-group">                                                <label class="form-label">                                                    جنسیت:                                                </label>                                                <div class="atbdp-radio-list">                                                    <div class="custom-control custom-radio">                                                        <input type="radio" id="male" name="gender"                                                               class="custom-control-input" checked="checked">                                                        <label class="custom-control-label iranyekan-light"                                                               for="male">آقا</label>                                                    </div>                                                    <div class="custom-control custom-radio">                                                        <input type="radio" id="female" name="gender"                                                               class="custom-control-input">                                                        <label class="custom-control-label iranyekan-light"                                                               for="female">خانم</label>                                                    </div>                                                </div>                                            </div>                                        </div>                                    </div>                                    <!-- ends: .form-group -->                                </form>                            </div><!-- ends: .atbdb_content_module_contents -->                        </div><!-- ends: .atbd_content_module -->                    </div><!-- ends: .col-lg-10 -->                    <div class="col-lg-9 ">                        <div class="atbd_content_module">                            <div class="atbd_content_module__tittle_area">                                <div class="atbd_area_title">                                    <h4 class="aviny"><span class="la la-user"></span> اطلاعات تماس و آدرس</h4>                                </div>                            </div>                            <div class="atbdb_content_module_contents">                                <form action="/">                                    <div class="row">                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="mobile" class="form-label">                                                شماره موبایل                                                <span class="iranyekan-light text-danger">                                            (اجباری)                                        </span>                                            </label>                                            <input name="mobile" type="text" class="form-control" id="mobile"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="home-phone" class="form-label">                                                شماره تلفن منزل                                            </label>                                            <input name="home-phone" type="text" class="form-control" id="home-phone"                                                   placeholder="به همراه پیش شماره: مثل ****۰۳۵۳۸۲۴"                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="e-phone" class="form-label">                                                شماره موبایل رابط                                                <span class="iranyekan-light text-danger">                                            (جهت موارد اضطراری)                                        </span>                                            </label>                                            <input name="e-phone" type="text" class="form-control" id="e-phone"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="state" class="form-label">                                                استان محل سکونت                                            </label>                                            <input name="state" type="text" class="form-control" id="state"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="city" class="form-label">                                                شهر محل سکونت                                            </label>                                            <input name="city" type="text" class="form-control" id="city"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-12 col-xs-12">                                            <label for="address" class="form-label">                                                آدرس                                            </label>                                            <input name="address" type="text" class="form-control" id="address"                                                   placeholder=""                                                   required>                                        </div>                                    </div>                                    <!-- ends: .form-group -->                                </form>                            </div><!-- ends: .atbdb_content_module_contents -->                        </div><!-- ends: .atbd_content_module -->                    </div><!-- ends: .col-lg-10 -->                    <div class="col-lg-9 ">                        <div class="atbd_content_module">                            <div class="atbd_content_module__tittle_area">                                <div class="atbd_area_title">                                    <h4 class="aviny"><span class="la la-user"></span>فرم‌ ویژه دانش‌آموزان</h4>                                </div>                            </div>                            <div class="atbdb_content_module_contents">                                <form action="/">                                    <div class="row">                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="school" class="form-label">                                                مدرسه محل تحصیل                                                <span class="iranyekan-light text-danger">                                            (اجباری)                                        </span>                                            </label>                                            <input name="school" type="text" class="form-control" id="school"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="point" class="form-label">                                                معدل                                            </label>                                            <input name="point" type="text" class="form-control" id="point"                                                   placeholder="آخرین معدل تحصیلی"                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="school-field" class="form-label">                                                رشته تحصیلی                                                <span class="iranyekan-light text-danger">                                            (متوسطه دوره دوم)                                        </span>                                            </label>                                            <div class="select-basic">                                                <select name="degree" class="form-control">                                                    <option value="0">انتخاب کنید</option>                                                    <option value="1"> ریاضی و فیزیک</option>                                                    <option value="2">علوم تجربی</option>                                                    <option value="3">علوم انسانی</option>                                                    <option value="4">هنرستان</option>                                                </select>                                            </div>                                        </div>                                    </div>                                    <!-- ends: .form-group -->                                </form>                            </div><!-- ends: .atbdb_content_module_contents -->                        </div><!-- ends: .atbd_content_module -->                    </div><!-- ends: .col-lg-10 -->                    <div class="col-lg-9 ">                        <div class="atbd_content_module">                            <div class="atbd_content_module__tittle_area">                                <div class="atbd_area_title">                                    <h4 class="aviny"><span class="la la-user"></span>فرم‌ ویژه دانشجویان و فارغ‌التحصیلان</h4>                                </div>                            </div>                            <div class="atbdb_content_module_contents">                                <form action="/">                                    <div class="row">                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="soldiery" class="form-label">                                                وضعیت سربازی                                                <span class="iranyekan-light text-danger">                                            (آقایان)                                        </span>                                            </label>                                            <div class="select-basic">                                                <select name="soldiery" class="form-control">                                                    <option value="0">انتخاب کنید</option>                                                    <option value="1">دارای کارت پایان خدمت</option>                                                    <option value="2">معافیت دائم</option>                                                    <option value="3">معافیت موقت</option>                                                    <option value="4">معافیت تحصیلی</option>                                                </select>                                            </div>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="soldiery-place" class="form-label">                                                محل خدمت                                            </label>                                            <input name="soldiery-place" type="text" class="form-control" id="soldiery-place"                                                   placeholder=""                                                   required>                                        </div>                                        <div class="form-group col-lg-4 col-xs-12">                                            <label for="soldiery-end" class="form-label">                                                پایان خدمت                                            </label>                                            <input name="soldiery-place" type="text" class="form-control" id="soldiery-end"                                                   placeholder="سال پایان خدمت"                                                   required>                                        </div>                                        <div class="col-lg-4">                                            <div class="row">                                                <div class="col-lg-6">                                                    <div class="form-group">                                                        <label class="form-label">                                                            وضعیت تأهل:                                                        </label>                                                        <div class="atbdp-radio-list">                                                            <div class="custom-control custom-radio">                                                                <input type="radio" id="married" name="marriage"                                                                       class="custom-control-input" checked="checked">                                                                <label class="custom-control-label iranyekan-light"                                                                       for="married">متأهل</label>                                                            </div>                                                            <div class="custom-control custom-radio">                                                                <input type="radio" id="single" name="marriage"                                                                       class="custom-control-input">                                                                <label class="custom-control-label iranyekan-light"                                                                       for="single">مجرد</label>                                                            </div>                                                            <div class="custom-control custom-radio">                                                                <input type="radio" id="widow" name="marriage"                                                                       class="custom-control-input">                                                                <label class="custom-control-label iranyekan-light"                                                                       for="widow">فوت همسر</label>                                                            </div>                                                        </div>                                                    </div>                                                </div>                                                <div class="form-group col-lg-6 col-xs-12">                                                    <label for="children" class="form-label">                                                        تعداد فرزند                                                    </label>                                                    <input name="children" type="text" class="form-control" id="children"                                                           placeholder=""                                                           required>                                                </div>                                            </div>                                        </div>                                    </div>                                    <!-- ends: .form-group -->                                </form>                            </div><!-- ends: .atbdb_content_module_contents -->                        </div><!-- ends: .atbd_content_module -->                    </div><!-- ends: .col-lg-10 -->                    <div class="col-lg-9 text-center">                        <div class="btn_wrap list_submit m-top-25">                            <button type="submit" class="btn btn-primary btn-lg listing_submit_btn">ذخیره اطلاعات</button>                        </div>                    </div><!-- ends: .col-lg-10 -->                </div>            </div>        </div>        <!-- ends: .tab-pane -->    </div>    <!-- Modal -->    <div class="modal fade" id="modal-item-remove" tabindex="-1" role="dialog" aria-hidden="true">        <div class="modal-dialog modal-dialog-centered" role="document">            <div class="modal-content">                <div class="modal-body text-center p-top-40 p-bottom-50">                    <span class="la la-exclamation-circle color-warning"></span>                    <h1 class="display-3 m-bottom-10">Are you sure?</h1>                    <p class="m-bottom-30">Do you really want to delete this item?</p>                    <div class="d-flex justify-content-center">                        <button type="button" class="btn btn-secondary m-right-15" data-dismiss="modal">Cancel</button>                        <button type="button" class="btn btn-danger">Yes, Delete it!</button>                    </div>                </div>            </div>        </div>    </div></section><?php $this->view('templates/fe/login-modal'); ?><?php $this->view('templates/fe/signup-modal'); ?><?php $this->view('templates/fe/home-footer-part'); ?>