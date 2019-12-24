<?php

namespace HPayment\PaymentClasses;

use HPayment\Payment;
use HPayment\PaymentException;

defined('BASE_PATH') OR exit('No direct script access allowed');

class PaymentMabna extends Payment
{
    protected $methodStr = 'method';
    protected $urlStr = 'url';

    /**
     * Variables that return from gateway after request/advice
     * @var array
     */
    protected $returnedVarsName = [
        'respcode', 'respmsg', 'amount', 'invoiceid', 'payload', 'terminalid',
        'tracenumber', 'rrn', 'datePaid', 'digitalreceipt', 'issuerbank', 'billid',
        'payid', 'cardnumber', 'pincharge', 'refcharge', 'serialcharge'
    ];

    /**
     * @var array
     */
    protected $adviceVarsName = ['digitalreceipt', 'Tid'];

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
        self::PAYMENT_STATUS_REQUEST_MABNA => [
            self::PAYMENT_STATUS_OK_MABNA => 'OK',
            self::PAYMENT_STATUS_FAILED_MABNA => 'NOK',
            self::PAYMENT_STATUS_DUPLICATE_MABNA => 'Duplicate',
            -2 => 'Not Set'
        ],
        self::PAYMENT_STATUS_VERIFY_MABNA => [
            -1 => 'تراکنش پیدا نشد.',
            -2 => 'تراکنش قبلا Reverse شده است.',
            -3 => 'Total Error خطای عمومی – خطای Exception ها',
            -4 => 'امکان انجام درخواست برای این تراکنش وجود ندارد.',
            -5 => 'آدرس IP نامعتبر میباشد ) IP در لیست آدرسهای معرفی شده توسط پذیرنده موجود نمیباشد(',
            -6 => 'عدم فعال بودن سرویس برگشت تراکنش برای پذیرنده'
        ]
    ];

    /**
     * @var array
     */
    public $urls = [
        self::PAYMENT_URL_PAYMENT_MABNA => 'https://mabna.shaparak.ir:8080/Pay',
        self::PAYMENT_URL_BILL_MABNA => 'https://mabna.shaparak.ir:8080/Bill',
        self::PAYMENT_URL_BATCH_BILL_MABNA => 'https://mabna.shaparak.ir:8080/BatchBill',
        self::PAYMENT_URL_CHARGE_MABNA => 'https://mabna.shaparak.ir:8080/Charge',
        self::PAYMENT_URL_MOBILE_PAYMENT_MABNA => 'https://mabna.shaparak.ir:8080/Mpay',
        self::PAYMENT_URL_MOBILE_BILL_MABNA => 'https://mabna.shaparak.ir:8080/MBill',
        self::PAYMENT_URL_MOBILE_BATCH_BILL_MABNA => 'https://mabna.shaparak.ir:8080/MBatchBill',
        self::PAYMENT_URL_MOBILE_CHARGE_MABNA => 'https://mabna.shaparak.ir:8080/MCharge',
        self::PAYMENT_URL_VERIFY_MABNA => 'https://mabna.shaparak.ir:8081/V1/PeymentApi/Advice'
    ];

    /**
     * PaymentMabna constructor.
     */
    public function __construct()
    {
        $this->_parameters['method'] = self::PAYMENT_METHOD_POST;
    }

    /**
     * Handle requested operation that come from bank gateway
     *
     * @return PaymentMabna
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
     * @return mixed
     * @throws PaymentException
     */
    public function create_request($data)
    {
        if (is_array($data) && count($data)) {
            // Set advice url
            $this->_parameters[$this->urlStr] = self::PAYMENT_URL_PAYMENT_MABNA;
            // Check request
            $this->_request_check($data);
        }

        return $this;
    }

    /**
     * Send advice to bank gateway to complete payment transaction
     *
     * @param $data
     * @return PaymentMabna
     * @throws PaymentException
     */
    public function send_advice($data)
    {
        $sendData = array_intersect_key($data, array_flip($this->adviceVarsName));
        if (is_array($sendData) && count($sendData)) {
            // Set advice url
            $this->_parameters[$this->urlStr] = self::PAYMENT_URL_VERIFY_MABNA;
            // Check request
            $this->_request_check($sendData);
        }

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
     * @param $kind - a <i>PAYMENT_STATUS_*_MABNA</i> constant
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
        if (!isset($this->_parameters[$this->methodStr])) {
            throw new PaymentException('طریقه ارسال اطلاعات به درگاه مشخص نشده است.', self::BANK_ERROR_UNDEFINED_METHOD);
        }
        //  Handle error for request url existence
        if (!isset($this->_parameters[$this->urlStr])) {
            throw new PaymentException('آدرس URL برای ارسال درخواست تعریف نشده است.', self::BANK_ERROR_UNDEFINED_URL);
        }

        $handle = curl_init();

        curl_setopt_array($handle, [
            CURLOPT_URL => $this->_parameters[$this->urlStr],
            CURLOPT_CUSTOMREQUEST => $this->_parameters[$this->methodStr],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_POSTFIELDS => $data
        ]);

        $tmpResult = curl_exec($handle);

        if (curl_errno($handle)) {
            // TODO: store handle error in a variable
//            return ['errReq' => curl_error($handle)];
        }

        curl_close($handle);

        return $tmpResult;
    }
}
