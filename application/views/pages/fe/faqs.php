<section class="header-breadcrumb bgimage overlay overlay--dark">    <div class="bg_image_holder">        <img alt=""             src="<?= base_url($setting['pages']['all']['topImage']['image'], '/'); ?>">    </div>    <!-- menu part -->    <?php $this->view('templates/fe/home-menu-part', $data); ?>    <!-- End Menu part -->    <div class="breadcrumb-wrapper content_above">        <div class="container">            <div class="row">                <div class="col-lg-12 text-center">                    <h1 class="page-title aviny">سؤالات متداول</h1>                    <nav aria-label="breadcrumb">                        <ol class="breadcrumb">                            <li class="breadcrumb-item"><a href="<?= base_url('index'); ?>">اُمگا</a></li>                            <li class="breadcrumb-item active iranyekan-light" aria-current="page">سؤالات متداول</li>                        </ol>                    </nav>                </div>            </div>        </div>    </div><!-- ends: .breadcrumb-wrapper --></section><section class="faq-wrapper section-padding border-bottom">    <div class="container">        <div class="row">            <div class="col-lg-12">                <div class="section-title">                    <h2>سؤالات متداول پرسیده شده</h2>                </div>            </div><!-- ends: .col-lg-12 -->            <div class="col-lg-12">                <div class="faq-contents">                    <div class="atbd_content_module atbd_faqs_module">                        <div class="atbd_content_module__tittle_area">                            <div class="atbd_area_title">                                <h4 class="iranyekan-regular"><span class="la la-question-circle"></span>لیست سؤالات متداول</h4>                            </div>                        </div>                        <div class="atbdb_content_module_contents">                            <div class="atbdp-accordion">                                <?php foreach ($faq as $k => $aq): ?>                                <div class="accordion-single <?= $k == 0 ? 'selected' : ''; ?>">                                    <h3 class="faq-title"><a href="javascript:void(0);"><?= $aq['question']; ?></a></h3>                                    <p class="ac-body d-block">                                        <?= $aq['answer']; ?>                                    </p>                                </div>                                <?php endforeach; ?>                            </div>                        </div>                    </div><!-- ends: .atbd_content_module -->                </div><!-- ends: .faq-contents -->            </div><!-- ends: .col-lg-12 -->        </div>    </div></section><!-- ends: .faq-wrapper --><?php if (!$auth->isLoggedIn()): ?>    <?php $this->view('templates/fe/login-modal', $data); ?>    <?php $this->view('templates/fe/signup-modal', $data); ?><?php endif; ?><?php $this->view('templates/fe/home-footer-part', $data); ?>