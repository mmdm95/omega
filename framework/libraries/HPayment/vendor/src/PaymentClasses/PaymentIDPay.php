<?php

namespace HPayment\PaymentClasses;

use HPayment\Payment;
use HPayment\PaymentException;

defined('BASE_PATH') OR exit('No direct script access allowed');

class PaymentIDPay extends Payment
{
    protected $APIKeyStr = 'APIKey';
    protected $modeStr = 'mode';
    protected $urlStr = 'url';

    /**
     * Variables that return from gateway after request/advice
     * @var array
     */
    protected $returnedVarsName = [
        'status', 'track_id', 'id', 'order_id', 'amount', 'card_no', 'hashed_card_no', 'date'
    ];

    /**
     * Status array for bank success/error or any kind of status.
     * Must store status in this array like ---->   code => message
     * Exp. ---->
     *  array(
     *      100 => 'Payment complete.'
     *  )
     * Exp.2. ---->
     *  array(
     *      'success' =>
     *          array(
     *              100 => 'Payment complete.'
     *          )
     *  )
     * @var array
     */
    protected $_statusArr = [
        self::PAYMENT_STATUS_REQUEST_IDPAY => [
            11 => 'کاربر مسدود شده است.',
            12 => 'API Key یافت نشد.',
            13 => 'درخواست شما از {ip} ارسال شده است. این IP با IP های ثبت شده در وب سرویس همخوانی ندارد.',
            14 => 'وب سرویس تایید نشده است.',
            21 => 'حساب بانکی متصل به وب سرویس تایید نشده است.',
            31 => 'کد تراکنش id نباید خالی باشد.',
            32 => 'شماره سفارش order_id نباید خالی باشد.',
            33 => 'مبلغ نباید خالی باشد.',
            34 => 'مبلغ باید بیشتر از ۱۰،۰۰۰ ریال باشد.',
            35 => 'مبلغ باید کمتر از ۵۰۰،۰۰۰،۰۰۰ ریال باشد.',
            36 => 'مبلغ بیشتر از حد مجاز است.',
            37 => 'آدرس بازگشت callback نباید خالی باشد.',
            38 => 'درخواست شما از آدرس {domain} ارسال شده است. دامنه آدرس بازگشت callback با آدرس ثبت شده در وب سرویس همخوانی ندارد.',
            51 => 'تراکنش ایجاد نشد.',
            52 => 'استعلام نتیجه ای نداشت.',
            53 => 'تایید پرداخت امکان پذیر نیست.',
            54 => 'مدت زمان تایید پرداخت سپری شده است.',
        ],
        self::PAYMENT_STATUS_VERIFY_IDPAY => [
            -2 => 'هیچ وضعیتی تنظیم نشده‌است',
            self::PAYMENT_STATUS_FAILED_IDPAY => 'پرداخت انجام نشده است',
            2 => 'پرداخت ناموفق بوده است',
            3 => 'خطا رخ داده است',
            4 => 'بلوکه شده',
            5 => 'برگشت به پرداخت کننده',
            6 => 'برگشت خورده سیستمی',
            self::PAYMENT_STATUS_WAIT_IDPAY => 'در انتظار تایید پرداخت',
            self::PAYMENT_STATUS_OK_IDPAY => 'پرداخت موفق',
            self::PAYMENT_STATUS_DUPLICATE_IDPAY => 'پرداخت قبلا انجام شده',
            200 => 'به دریافت کننده واریز شد'
        ]
    ];

    /**
     * @var array
     */
    public $urls = [
        self::PAYMENT_URL_PAYMENT_IDPAY => 'https://api.idpay.ir/v1.1/payment',
        self::PAYMENT_URL_INQUIRY_IDPAY => 'https://api.idpay.ir/v1.1/payment/inquiry',
        self::PAYMENT_URL_VERIFY_IDPAY => 'https://api.idpay.ir/v1.1/payment/verify'
    ];

    /**
     * PaymentIDPay constructor.
     * @param string $APIKey
     */
    public function __construct($APIKey = '')
    {
        //Set APIKey
        $this->_parameters[$this->APIKeyStr] = $APIKey;
        // Set mode
        $this->_parameters['mode'] = self::PAYMENT_MODE_PRODUCTION_IDPAY;
    }

    /**
     * Handle requested operation that come from bank gateway
     *
     * @return PaymentIDPay
     */
    public function handle_request()
    {
        // Reset result array
        $this->_result = [];
        if ($_SERVER["REQUEST_METHOD"] == self::PAYMENT_METHOD_POST) {
            foreach ($this->returnedVarsName as $name) {
                ${$name} = isset($_POST[$name]) ? $this->_escape_data($_POST[$name]) : null;
                $this->_result[$name] = ${$name};
            }
        }

        return $this;
    }

    /**
     * Create request by send a request to bank gateway
     *
     * @param $data
     * @return PaymentIDPay
     * @throws PaymentException
     */
    public function create_request($data)
    {
        // Set request url
        $this->_parameters[$this->urlStr] = self::PAYMENT_URL_PAYMENT_IDPAY;
        // Check request
        $this->_request_check($data);

        return $this;
    }

    /**
     * Send advice to bank gateway to complete payment transaction
     *
     * @param $data
     * @return PaymentIDPay
     * @throws PaymentException
     */
    public function send_advice($data)
    {
        // Set advice url
        $this->_parameters[$this->urlStr] = self::PAYMENT_URL_VERIFY_IDPAY;
        // Check request
        $this->_request_check($data);

        return $this;
    }

    /**
     * Return the result that was tracked from request/verify/advice
     *
     * @return array
     */
    public function get_result()
    {
        return $this->_result;
    }

    /**
     * Get message of a request/advice code for display purposes
     *
     * @param $code
     * @param $kind - a <i>PAYMENT_STATUS_*_IDPAY</i> constant
     * @return string|bool - return <b>string</b> if there is message otherwise return <b>false</b>
     */
    public function get_message($code, $kind) {
        if (isset($code) && isset($this->_statusArr[$kind]) && key_exists($code, $this->_statusArr[$kind])) {
            return $this->_statusArr[$kind][$code];
        }
        return false;
    }

    /**
     * Check request $data before send it to _send_request method
     *
     * @param $data
     * @throws PaymentException
     */
    protected function _request_check($data)
    {
        if (is_array($data) && count($data)) {
            // Send request to gateway
            $this->_result = json_decode($this->_send_request($data), true);
        }
    }

    /**
     * Send request/advice to bank gateway
     *
     * @param $data
     * @return array|bool|string
     * @throws PaymentException
     */
    protected function _send_request($data)
    {
        // Reset global result array
        $this->_result = [];

        // Handle error for API Key existence
        if (!isset($this->_parameters[$this->APIKeyStr])) {
            throw new PaymentException('API KEY برای درگاه بانک تعریف نشده است.', self::BANK_ERROR_BAD_API_KEY);
        }
        //  Handle error for request url existence
        if (!isset($this->_parameters[$this->urlStr])) {
            throw new PaymentException('آدرس URL برای ارسال درخواست تعریف نشده است.', self::BANK_ERROR_UNDEFINED_URL);
        }

        $headers = [
            'Content-Type: application/json',
            'X-API-KEY: ' . $this->_parameters[$this->APIKeyStr],
        ];

        if (isset($this->_parameters[$this->modeStr]) && $this->_parameters[$this->modeStr] == self::PAYMENT_MODE_DEVELOPMENT_IDPAY) {
            $headers[] = 'X-SANDBOX: 1';
        }

        $handle = curl_init();

        curl_setopt_array($handle, [
            CURLOPT_URL => $this->_parameters[$this->urlStr],
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $tmpResult = curl_exec($handle);

        if (curl_errno($handle)) {
            // TODO: store handle error in a variable
//            return ['errReq' => curl_error($handle)];
        }

        if (isset($this->_parameters[$this->modeStr]) && $this->_parameters[$this->modeStr] == self::PAYMENT_MODE_DEVELOPMENT_IDPAY) {
            $httpcode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        }

        curl_close($handle);

        return $tmpResult;
    }
}
