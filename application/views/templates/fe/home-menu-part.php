<div class="col-lg-12">
    <div class="menu-fullwidth">
        <div class="logo-wrapper order-lg-0 order-sm-1">
            <div class="logo logo-top">
                <a href="index.php"><img src="<?= asset_url('fe/images/omega-logo-S.png') ?>" alt="logo image"
                                         class="img-fluid"></a>
            </div>
        </div><!-- ends: .logo-wrapper -->
        <div class="menu-container order-lg-1 order-sm-0">
            <div class="d_menu">
                <nav class="navbar navbar-expand-lg mainmenu__menu">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#direo-navbar-collapse"
                            aria-controls="direo-navbar-collapse" aria-expanded="false"
                            aria-label="Toggle navigation">
                                                <span class="navbar-toggler-icon icon-menu"><i
                                                            class="la la-reorder"></i></span>
                    </button>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="direo-navbar-collapse">
                        <ul class="navbar-nav">
                            <li>
                                <a href="index.php">صفحه اصلی</a>
                            </li>
                            <li>
                                <a href="allEvents">طرح‌ها</a>
                            </li>

                            <li>
                                <a href="index.php">اخبار و اطلاعیه‌ها</a>
                            </li>

                            <li>
                                <a href="index.php">فایل‌های آموزشی</a>
                            </li>
<!--                            <li class="dropdown has_dropdown">-->
<!--                                <a href="#" class="dropdown-toggle" id="drop3" role="button"-->
<!--                                   data-toggle="dropdown" aria-haspopup="true"-->
<!--                                   aria-expanded="false">طرح‌ها</a>-->
<!--                                <ul class="dropdown-menu" aria-labelledby="drop3">-->
<!--                                    <li><a href="all-listings-grid.html">Listing Grid</a></li>-->
<!--                                    <li><a href="all-listings-list.html">Listing List</a></li>-->
<!--                                    <li><a href="listing-details.html">Listing Details</a></li>-->
<!--                                    <li><a href="add-listing.html">Add Listing</a></li>-->
<!--                                </ul>-->
<!--                            </li>-->
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
                            <a href="" class="access-link"
                               data-toggle="modal"
                               data-target="#login_modal">ورود</a>
                        </li>
                        <li>
                            <a href=""
                               data-toggle="modal"
                               data-target="#signup_modal"
                               class="btn btn-xs btn-gradient btn-gradient-two">
                                <i class="la la-user"></i>ثبت نام
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