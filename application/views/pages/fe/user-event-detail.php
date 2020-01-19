<?phpuse HPayment\Payment;?>    <section class="header-breadcrumb bgimage overlay overlay--dark">        <div class="bg_image_holder">            <img alt=""                 src="<?= base_url($setting['pages']['all']['topImage']['image'], '/'); ?>">        </div>        <!-- menu part -->        <?php $this->view('templates/fe/home-menu-part', $data); ?>        <!-- End Menu part -->        <div class="breadcrumb-wrapper content_above">            <div class="container">                <div class="row">                    <div class="col-lg-12 text-center">                        <h1 class="page-title aviny">                            <?= $event['title']; ?>                        </h1>                        <nav aria-label="breadcrumb">                            <ol class="breadcrumb">                                <li class="breadcrumb-item"><a href="<?= base_url('index'); ?>">صفحه اصلی</a></li>                                <li class="breadcrumb-item"><a href="<?= base_url('user/dashboard#listings'); ?>">طرح‌های                                        من</a></li>                                <li class="breadcrumb-item active iranyekan-light" aria-current="page">                                    جزئیات ثبت نام                                </li>                            </ol>                        </nav>                    </div>                </div>            </div>        </div><!-- ends: .breadcrumb-wrapper -->    </section>    <section class="payment_receipt section-bg section-padding-strict">        <div class="container">            <div class="row">                <div class="col-lg-10 offset-lg-1">                    <?php if (isset($paymentErrors) && count($paymentErrors)): ?>                        <div class="alert alert-danger alert-dismissible fade show" role="alert">                            <?php foreach ($paymentErrors ?? [] as $error): ?>                                <p class="my-1">                                    <?= $error; ?>                                </p>                            <?php endforeach; ?>                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">                                <span aria-hidden="true">&times;</span>                            </button>                        </div>                    <?php endif; ?>                    <div class="payment_receipt--wrapper">                        <div class="payment_receipt--contents">                            <?php if (!empty($event['payed_amount'])): ?>                                <h2 class="atbd_thank_you aviny">                                    با تشکر از خرید شما!                                </h2>                            <?php else: ?>                                <div class="alert alert-danger">                                    <strong>                                        پرداخت انجام نشده است.                                    </strong>                                    برای تکمیل ثبت نام لطفا مبلغ پیش‌پرداخت را واریز نمایید.                                </div>                            <?php endif; ?>                            <div class="atbd_payment_instructions">                                <p>                                    دوست عزیز در صورت هر گونه مغایرت اطلاعات با شماره‌های پشتیبانی این طرح ارتباط برقرار                                    کنید و یا در روز شروع طرح مشکل را با مسئولین مطرح کنید.                                </p>                                <h4 class="aviny">                                    جزئیات سفارش:                                </h4>                                <ul class="list-unstyled">                                    <li>                                        شماره فاکتور:                                        <span class="order-code">                                            <?= $event['factor_code']; ?>                                        </span>                                    </li>                                    <li>                                        نام و نام خانوادگی:                                        <span>                                            <?= $event['full_name']; ?>                                        </span>                                    </li>                                    <li>                                        تاریخ ثبت طرح:                                        <span>                                            <?= jDateTime::date('j F Y در ساعت H:i', $event['created_at']); ?>                                        </span>                                    </li>                                </ul>                            </div><!-- ends: .atbd_payment_instructions -->                            <p class="atbd_payment_summary aviny">آیتم‌های انتخابی در طرح:</p>                            <?php if (!empty($event['base_price'])): ?>                                <div class="alert bg-secondary text-white d-flex">                                    <span class="col">                                        قیمت پایه طرح:                                    </span>                                    <strong class="font-size-md">                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($event['base_price'], true))); ?>                                        تومان                                    </strong>                                </div>                            <?php endif; ?>                            <?php foreach ($event['options'] as $k => $option): ?>                                <div class="p-3 bg-dark">                                    <h4 class="iranyekan-regular text-white m-0">                                        <?= $option['title']; ?>                                    </h4>                                </div>                                <?php                                $isRadio = $option['radio'] == 2 ? true : false;                                ?>                                <div class="checkout-table table-responsive mb-4">                                    <table id="directorist-checkout-table<?= ($k + 1); ?>"                                           class="table table-bordered <?= $isRadio ? 'table-single-select' : 'table-multi-select'; ?>">                                        <thead>                                        <tr>                                            <th>عنوان و توضیح</th>                                            <th>قیمت</th>                                        </tr>                                        </thead>                                        <tbody>                                        <?php foreach ($option['name'] as $k2 => $name): ?>                                            <tr>                                                <td>                                                    <h4 class="aviny"><?= $name; ?></h4>                                                    <?php if (!empty($option['desc'][$k2])): ?>                                                        <p class="iranyekan-light">                                                            <?= $option['desc'][$k2]; ?>                                                        </p>                                                    <?php endif; ?>                                                </td>                                                <td>                                                    <?php if (is_numeric($option['price'][$k2])): ?>                                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($option['price'][$k2], true))); ?>                                                        تومان                                                    <?php else: ?>                                                        <?= $option['price'][$k2]; ?>                                                    <?php endif; ?>                                                </td>                                            </tr>                                        <?php endforeach; ?>                                        </tbody>                                    </table>                                </div><!-- ends: .checkout-table -->                            <?php endforeach; ?>                            <div class="row atbd_payment_summary_wrapper">                                <div class="col-md-12">                                    <p class="atbd_payment_summary">                                        جزئیات پرداخت:                                    </p>                                </div>                                <div class="col-lg-12">                                    <div class="table-responsive">                                        <table class="table table-bordered">                                            <tbody>                                            <tr>                                                <td>شماره فاکتور:</td>                                                <td class="order-code">                                                    <?= $event['factor_code']; ?>                                                </td>                                            </tr>                                            <tr>                                                <td>                                                    مبلغ کل:                                                </td>                                                <td>                                                    <?= convertNumbersToPersian(number_format(convertNumbersToPersian($event['total_amount'], true))); ?>                                                    تومان                                                </td>                                            </tr>                                            <tr>                                                <td>مبلغ پرداخت شده:</td>                                                <td>                                                    <?php if (!empty($event['payed_amount'])): ?>                                                        <?= convertNumbersToPersian(number_format(convertNumbersToPersian($event['payed_amount'], true))); ?>                                                        تومان                                                    <?php else: ?>                                                        <i class="la la-minus"></i>                                                    <?php endif; ?>                                                </td>                                            </tr>                                            <tr>                                                <td>مبلغ باقی‌مانده:</td>                                                <td>                                                    <?php                                                    $remainAmount = convertNumbersToPersian((int)$event['total_amount'], true) - convertNumbersToPersian((int)$event['payed_amount'], true);                                                    ?>                                                    <?= convertNumbersToPersian(number_format($remainAmount)); ?>                                                    تومان                                                </td>                                            </tr>                                            </tbody>                                        </table>                                    </div>                                </div>                                <?php if (count($event['payments'])): ?>                                    <div class="col-lg-12">                                        <div class="table-responsive">                                            <table class="table table-bordered">                                                <thead>                                                <tr>                                                    <th>وضعیت پرداخت</th>                                                    <th>کد رهگیری</th>                                                    <th>مبلغ پرداخت شده</th>                                                    <th>تاریخ انجام تراکنش</th>                                                </tr>                                                </thead>                                                <tbody>                                                <?php foreach ($event['payments'] as $payment): ?>                                                    <tr>                                                        <td>                                                            <?php                                                            if ($payment['payment_status'] == Payment::PAYMENT_TRANSACTION_SUCCESS_ZARINPAL ||                                                                $payment['payment_status'] == Payment::PAYMENT_TRANSACTION_DUPLICATE_ZARINPAL):                                                                ?>                                                                <span class="text-success">                                                                موفق                                                            </span>                                                            <?php else: ?>                                                                <span class="text-danger">                                                                ناموفق                                                            </span>                                                            <?php endif; ?>                                                        </td>                                                        <td>                                                            <?= convertNumbersToPersian($event['payment_code'] ?? ''); ?>                                                        </td>                                                        <td>                                                            <?= convertNumbersToPersian(number_format(convertNumbersToPersian($payment['amount'], true))); ?>                                                            تومان                                                        </td>                                                        <td>                                                            <?= jDateTime::date('j F Y در ساعت H:i', $payment['payment_date']); ?>                                                        </td>                                                    </tr>                                                <?php endforeach; ?>                                                </tbody>                                            </table>                                        </div>                                    </div>                                <?php endif; ?>                            </div><!-- ends: .atbd_payment_summary_wrapper -->                            <?php if ($remainAmount > 0): ?>                                <form action="<?= base_url('user/event' . implode('/', $param)); ?>" method="post">                                    <?= $form_token_payment; ?>                                    <div class="d-flex align-items-end">                                        <div class="col">                                            <label for="pay-select">انتخاب مبلغ پرداختی</label>                                            <div class="select-basic">                                                <select name="pay" class="form-control" id="pay-select">                                                    <option value="0">                                                        انتخاب کنید                                                    </option>                                                    <?php                                                    $len = (int)((int)$remainAmount / (int)$event['min_price']);                                                    ?>                                                    <?php for ($i = 1; $i <= $len; $i++): ?>                                                        <option value="<?= $i; ?>">                                                            <?php if ($i == $len): ?>                                                                <?= convertNumbersToPersian(number_format(convertNumbersToPersian((int)$remainAmount, true))); ?>                                                                تومان                                                            <?php else: ?>                                                                <?php if (1 == $i): ?>                                                                    <?= convertNumbersToPersian(number_format(convertNumbersToPersian((int)$event['min_price'] * $i, true))); ?>                                                                    تومان                                                                    (پیش‌پرداخت)                                                                <?php else: ?>                                                                    <?= convertNumbersToPersian(number_format(convertNumbersToPersian((int)$event['min_price'] * $i, true))); ?>                                                                    تومان                                                                <?php endif; ?>                                                            <?php endif; ?>                                                        </option>                                                    <?php endfor; ?>                                                </select>                                            </div>                                        </div>                                        <div class="text-center">                                            <button type="submit" class="btn btn-success">                                                پرداخت                                            </button>                                        </div>                                    </div>                                </form>                            <?php endif; ?>                            <?php if (!empty($event['payed_amount'])): ?>                                <form action="<?= base_url('user/event' . implode('/', $param)); ?>" method="post">                                    <?= $form_token_ticket; ?>                                    <div class="text-center m-top-30">                                        <button type="submit" class="btn btn-primary" name="ticket">                                            دریافت بلیط                                        </button>                                    </div>                                </form>                            <?php endif; ?>                        </div><!-- ends: .payment_receipt--contents -->                    </div><!-- ends: .payment_receipt--wrapper -->                </div><!-- ends: .col-lg-10 -->            </div>        </div>    </section><!-- ends: .atbd_payment_recipt --><?php $this->view('templates/fe/home-footer-part', $data); ?>