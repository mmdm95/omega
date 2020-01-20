<div class="mainmenu-wrapper">
    <div class="menu-area menu1 menu--light">
        <div class="top-menu-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="menu-fullwidth">
                            <div class="logo-wrapper order-lg-0 order-sm-1">
                                <div class="logo logo-top">
                                    <a href="<?= base_url('index'); ?>">
                                        <img src="<?= base_url($logo); ?>"
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
                                                    <a href="<?= base_url('event/events'); ?>">طرح‌ها</a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url('blog'); ?>">اخبار و اطلاعیه‌ها</a>
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
                                            <?php if ($auth->isLoggedIn()): ?>
                                                <li>
                                                    <a href="<?= base_url('user/dashboard'); ?>"
                                                       class="access-link">داشبورد</a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url('logout'); ?>"
                                                       class="btn btn-xs btn-gradient btn-gradient-one">
                                                        <i class="la la-power-off mr-1"></i>
                                                        خروج
                                                    </a>
                                                </li>
                                            <?php else: ?>
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
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end .author-area -->
                                <div class="offcanvas-menu d-none">
                                    <a href="javascript:void(0);" class="offcanvas-menu__user"><i
                                                class="la la-user"></i></a>
                                    <div class="offcanvas-menu__contents">
                                        <a href="javascript:void(0);" class="offcanvas-menu__close"><i
                                                    class="la la-times-circle"></i></a>
                                        <div class="author-avatar">
                                            <?php if (isset($identity->image)): ?>
                                                <img src="<?= base_url($identity->image); ?>"
                                                     alt="<?= $identity->username ?? ''; ?>"
                                                     class="rounded-circle w-50 user-profile-image">
                                            <?php endif; ?>
                                        </div>
                                        <ul class="list-unstyled">
                                            <?php if ($auth->isLoggedIn()): ?>
                                                <li>
                                                    <a href="<?= base_url('user/dashboard'); ?>"
                                                       class="access-link">داشبورد</a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url('logout'); ?>">
                                                        خروج
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <a href="javascript:void(0);" class="access-link"
                                                       data-toggle="modal"
                                                       data-target="#login_modal">ورود</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       data-toggle="modal"
                                                       data-target="#signup_modal">
                                                        ثبت نام
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div><!-- ends: .author-info -->
                                </div><!-- ends: .offcanvas-menu -->
                            </div><!-- ends: .menu-right -->
                        </div>
                    </div>
                </div>
                <!-- end /.row -->
            </div>
            <!-- end /.container -->
        </div>
        <!-- end  -->
    </div>
</div><!-- ends: .mainmenu-wrapper -->