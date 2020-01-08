<div class="col-lg-12">
    <div class="menu-fullwidth">
        <div class="logo-wrapper order-lg-0 order-sm-1">
            <div class="logo logo-top">
                <a href="<?= base_url('index'); ?>">
                    <img src="<?= $logo; ?>"
                         alt="<?= $setting['main']['title'] ?? ''; ?>"
                         class="img-fluid">
                </a>
            </div>
        </div><!-- ends: .logo-wrapper -->
        <div class="menu-container order-lg-1 order-sm-0">
            <div class="d_menu">
                <nav class="navbar navbar-expand-lg mainmenu__menu">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#direo-navbar-collapse"
                            aria-controls="direo-navbar-collapse" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon icon-menu">
                            <i class="la la-reorder"></i>
                        </span>
                    </button>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="direo-navbar-collapse">
                        <ul class="navbar-nav">
                            <li>
                                <a href="<?= base_url('index'); ?>">صفحه اصلی</a>
                            </li>
                            <li>
                                <a href="<?= base_url('events'); ?>">طرح‌ها</a>
                            </li>
                            <li>
                                <a href="<?= base_url('articles'); ?>">اخبار و اطلاعیه‌ها</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
            </div>
        </div>
        <div class="menu-right order-lg-2 order-sm-2">
            <!-- start .author-area -->
            <div class="author-area">
                <div class="author__access_area">
                    <ul class="d-flex list-unstyled align-items-center">
                        <li>
                            <a href="javascript:void(0);" class="access-link"
                               data-toggle="modal"
                               data-target="#login_modal">ورود</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"
                               data-toggle="modal"
                               data-target="#signup_modal"
                               class="btn btn-xs btn-gradient btn-gradient-two">
                                <i class="la la-user mr-1"></i>
                                ثبت نام
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- end .author-area -->
            <div class="offcanvas-menu d-none">
                <a href="" class="offcanvas-menu__user"><i class="la la-user"></i></a>
                <div class="offcanvas-menu__contents">
                    <a href="" class="offcanvas-menu__close"><i class="la la-times-circle"></i></a>
                    <div class="author-avatar">
                        <img src="img/author-avatar.png" alt="" class="rounded-circle">
                    </div>
                    <ul class="list-unstyled">
                        <li><a href="dashboard-listings.html">My Profile</a></li>
                        <li><a href="dashboard-listings.html">My Listing</a></li>
                        <li><a href="dashboard-listings.html">Favorite Listing</a></li>
                        <li><a href="add-listing.html">Add Listing</a></li>
                        <li><a href="">Logout</a></li>
                    </ul>
                </div><!-- ends: .author-info -->
            </div><!-- ends: .offcanvas-menu -->
        </div><!-- ends: .menu-right -->
    </div>
</div>