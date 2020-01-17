<?php if (isset($registerErrors) && count($registerErrors)): ?>
    <script>
        (function ($) {
            'use strict';

            $(function () {
                $('#signup_modal').modal('show');
                $('#login_modal').modal('hide');
            });
        })(jQuery);
    </script>
<?php endif; ?>

<div class="modal fade" id="signup_modal" tabindex="-1" role="dialog" aria-labelledby="signup_modal_label"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title iranyekan-regular" id="signup_modal_label"><i class="la la-lock mr-1"></i>عضویت
                    در
                    سایت</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (isset($registerErrors) && count($registerErrors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="list-unstyled m-0">
                            <?php foreach ($registerErrors as $err): ?>
                                <li>
                                    <i class="la la-minus" aria-hidden="true"></i>
                                    <?= $err; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif ?>

                <form action="<?= base_url(CONTROLLER . '/' . ACTION); ?><?= isset($param) && !empty($param) ? '/' . implode('/', $param) : ''; ?>?back_url=<?= URITracker::get_last_uri(); ?>"
                      id="signup-form" method="post">
                    <?= $form_token_register; ?>

                    <input name="mobile" type="text" class="form-control" placeholder="شماره موبایل" required
                           value="<?= $registerValues['mobile'] ?? ''; ?>">
                    <input name="password" type="password" class="form-control" placeholder="رمز عبور" required>
                    <input name="re_password" type="password" class="form-control" placeholder="تکرار رمز عبور"
                           required>
                    <label>نقش خود را انتخاب کنید:</label>
                    <div class="custom-control custom-radio">
                        <input value="<?= AUTH_ROLE_STUDENT ?>" type="radio" id="student" name="role"
                               class="custom-control-input" checked="checked" required>
                        <label class="custom-control-label iranyekan-light"
                               for="student">
                            دانش‌آموز
                        </label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input value="<?= AUTH_ROLE_COLLEGE_STUDENT; ?>" type="radio" id="collegian" name="role"
                               class="custom-control-input" required>
                        <label class="custom-control-label iranyekan-light"
                               for="collegian">
                            دانشجو
                        </label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input value="<?= AUTH_ROLE_GRADUATE; ?>" type="radio" id="graduate" name="role"
                               class="custom-control-input" required>
                        <label class="custom-control-label iranyekan-light"
                               for="graduate">
                            فارغ‌التحصیل
                        </label>
                    </div>
                    <br>
                    <div class="form-group text-center">
                        <div class="form-group form-account-captcha" data-captcha-url="<?= ACTION; ?>">
                            <img src="" alt="captcha">
                            <button type="button" class="btn btn-link ml-2 mb-0 form-captcha">
                                <i class="la la-refresh font-size-lg"></i>
                            </button>
                        </div>
                        <div>
                            <input name="registerCaptcha" class="form-control ltr text-left" type="text"
                                   placeholder="کد تصویر بالا" pattern="[(a-z)(A-Z)(0-9)]{6}">
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-block btn-lg btn-gradient btn-gradient-two">ثبت نام</button>
                </form>
                <div class="form-excerpts">
                    <ul class="list-unstyled">
                        <li>
                            آیا قبلا عضو بوده‌اید؟
                            <a href="javascript:void(0);"
                               onclick="jQuery('#signup_modal').modal('hide');"
                               data-toggle="modal"
                               data-target="#login_modal">وارد شوید!</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
