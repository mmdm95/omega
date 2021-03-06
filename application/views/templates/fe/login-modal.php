<?php if (isset($loginErrors) && count($loginErrors)): ?>
    <script>
        (function ($) {
            'use strict';

            $(function () {
                $('#login_modal').modal('show');
                $('#signup_modal').modal('hide');
            });
        })(jQuery);
    </script>
<?php endif; ?>

<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="login_modal_label"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title iranyekan-regular" id="login_modal_label"><i class="la la-unlock mr-1"></i>ورود
                    کاربران</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (isset($loginErrors) && count($loginErrors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="list-unstyled m-0">
                            <?php foreach ($loginErrors as $err): ?>
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
                <?php endif; ?>

                <form action="<?= base_url(CONTROLLER . '/' . ACTION); ?><?= isset($param) && !empty($param) ? '/' . implode('/', $param) : ''; ?>?back_url=<?= URITracker::get_last_uri(); ?>"
                      id="login-form" method="post">
                    <?= $form_token_login; ?>

                    <input type="text" name="username" class="form-control" placeholder="شماره موبایل" required
                           value="<?= $loginValues['username'] ?? ''; ?>">
                    <input type="password" name="password" class="form-control" placeholder="رمز عبور" required>
                    <div class="keep_signed custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="checkbox" class="custom-control-input" name="remember"
                               id="keep_signed_in">
                        <label for="keep_signed_in" class="not_empty custom-control-label">مرا به خاطر بسپار!</label>
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
                            <input name="loginCaptcha" class="form-control ltr text-left" type="text"
                                   placeholder="کد تصویر بالا" pattern="[(a-z)(A-Z)(0-9)]{6}">
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-block btn-lg btn-gradient btn-gradient-two">ورود</button>
                </form>
                <div class="form-excerpts">
                    <ul class="list-unstyled">
                        <li>
                            هنوز عضو نشدید؟
                            <a href="javascript:void(0);"
                               onclick="jQuery('#login_modal').modal('hide');"
                               data-toggle="modal"
                               data-target="#signup_modal">ثبت نام کنید!</a>
                        </li>
                        <li><a href="">بازیابی رمز عبور</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
