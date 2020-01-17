<footer class="footer-three footer-grey p-top-95">
    <div class="footer-top p-bottom-25">
        <div class="container">
            <div class="row">
                <?php if (isset($setting['footer']['sections'])): ?>
                    <?php foreach ($setting['footer']['sections'] as $section): ?>
                        <?php if (!empty($section['title'])): ?>
                            <div class="col-lg-3 col-sm-6">
                                <div class="widget widget_pages">
                                    <h2 class="widget-title aviny">
                                        <?= $section['title'] ?? ''; ?>
                                    </h2>
                                    <?php if (isset($section['links'])): ?>
                                        <ul class="list-unstyled">
                                            <?php foreach ($section['links'] as $link): ?>
                                                <?php if (!empty($link['text'])): ?>
                                                    <li class="page-item">
                                                        <a href="<?= $link['link'] ?? '#'; ?>">
                                                            <?= $link['text'] ?? ''; ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (isset($setting['footer']['socials'])): ?>
                    <?php if (isset($setting['footer']['socials']['email']) && !empty($setting['footer']['socials']['email']) ||
                        isset($setting['footer']['socials']['telegram']) && !empty($setting['footer']['socials']['telegram']) ||
                        isset($setting['footer']['socials']['instagram']) && !empty($setting['footer']['socials']['instagram']) ||
                        isset($setting['footer']['socials']['facebook']) && !empty($setting['footer']['socials']['facebook'])): ?>
                        <div class="col-lg-3 col-sm-6">
                            <div class="widget widget_social">
                                <h2 class="widget-title aviny">با ما در شبکه‌های اجتماعی همراه باشید.</h2>
                                <ul class="list-unstyled social-list">
                                    <?php if (isset($setting['footer']['socials']['email']) && !empty($setting['footer']['socials']['email'])): ?>
                                        <li>
                                            <a href="mailto:<?= $setting['footer']['socials']['email']; ?>">
                                                <span class="mail"><i class="la la-envelope"></i></span>
                                                پست الکترونیکی
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (isset($setting['footer']['socials']['telegram']) && !empty($setting['footer']['socials']['telegram'])): ?>
                                        <li>
                                            <a href="<?= $setting['footer']['socials']['telegram']; ?>">
                                                <span class="twitter"><i class="fab fa-telegram"></i></span>
                                                تلگرام
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (isset($setting['footer']['socials']['instagram']) && !empty($setting['footer']['socials']['instagram'])): ?>
                                        <li>
                                            <a href="<?= $setting['footer']['socials']['instagram']; ?>">
                                                <span class="instagram"><i class="fab fa-instagram"></i></span>
                                                اینستاگرام
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (isset($setting['footer']['socials']['facebook']) && !empty($setting['footer']['socials']['facebook'])): ?>
                                        <li>
                                            <a href="<?= $setting['footer']['socials']['facebook']; ?>">
                                                <span class="facebook"><i class="fab fa-facebook-f"></i></span>
                                                فیسبوک
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div><!-- ends: .widget -->
                        </div><!-- ends: .col-lg-3 -->
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div><!-- ends: .footer-top -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-bottom--content">
                        <p class="m-0 copy-text">
                            ©
                            <?= convertNumbersToPersian(date('Y')); ?>
                            امگا. طراحی و توسعه توسط
                            <a href="">
                                هیوا
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- ends: .footer-bottom -->
</footer><!-- ends: .footer -->
