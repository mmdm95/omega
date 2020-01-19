<?php

namespace HPayment\PaymentClasses;

use HPayment\Payment;
use HPayment\PaymentException;
use SoapClient;

defined('BASE_PATH') OR exit('No direct script access allowed');

class PaymentZarinPal extends Payment
{
    protected $soapStr = 'url';
    protected $MerchantIDStr = 'MerchantID';
    protected $amountStr = 'amount';
    protected $statusStr = 'status';

    /**
     * Variables that return from gateway after request/advice
     * @var array
     */
    protected $returnedVarsName = [
        self::PAYMENT_RETURNED_AUTHORITY_ZARINPAL,
        self::PAYMENT_RETURNED_STATUS_ZARINPAL
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
        -1 => 'اطلاعات ارسال شده ناقص است.',
        -2 => 'IP و یا مرچنت کد پذیرنده صحیح نیست.',
        -3 => 'با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد.',
        -4 => 'سطح تاييد پذيرنده پايين تر از سطح نقره اي است.',
        -11 => 'درخواست مورد نظر يافت نشد.',
        -12 => 'امكان ويرايش درخواست ميسر نمي باشد.',
        -21 => 'هيچ نوع عمليات مالي براي اين تراكنش يافت نشد.',
        -22 => 'تراكنش نا موفق ميباشد.',
        -33 => 'رقم تراكنش با رقم پرداخت شده مطابقت ندارد.',
        -34 => 'سقف تقسيم تراكنش از لحاظ تعداد يا رقم عبور نموده است',
        -40 => 'اجازه دسترسي به متد مربوطه وجود ندارد.',
        -41 => 'غيرمعتبر ميباشد. AdditionalData اطلاعات ارسال شده مربوط به',
        -42 => 'مدت زمان معتبر طول عمر شناسه پرداخت بايد بين 30 دقيقه تا 45 روز باشد.',
        -54 => 'درخواست مورد نظر آرشيو شده است.',
        100 => 'عمليات با موفقيت انجام گرديده است.',
        101 => 'تراكنش انجام شده است. PaymentVerification عمليات پرداخت موفق بوده و قبلا'
    ];

    /**
     * @var SoapClient
     */
    protected $_client;

    /**
     * @var array
     */
    public $urls = [
        self::PAYMENT_URL_SOAP_ZARINPAL => 'https://www.zarinpal.com/pg/services/WebGate/wsdl',
        self::PAYMENT_URL_PAYMENT_ZARINPAL => 'https://www.zarinpal.com/pg/StartPay/'
    ];

    /**
     * PaymentZarinPal constructor.
     * @param string $MerchantID - Set MerchantID here to have it on all pages
     * Exp. eb361068-16f6-11e8-8e09-000c295eb8fc
     * @param null $soapUrl
     */
    public function __construct($MerchantID = 'eb361068-16f6-11e8-8e09-000c295eb8fc', $soapUrl = null)
    {
        if (is_null($soapUrl)) {
            $this->_parameters[$this->soapStr] = $this->urls[self::PAYMENT_URL_SOAP_ZARINPAL];
        } else {
            $this->_parameters[$this->soapStr] = $soapUrl;
        }
        // URL also can be ir.zarinpal.com or de.zarinpal.com
        $this->_client = new SoapClient($this->_parameters[$this->soapStr], ['encoding' => 'UTF-8']);
        // Set MerchantID here to have it for all pages
        $this->_parameters[$this->MerchantIDStr] = $MerchantID;
    }

    /**
     * Handle requested operation that come from bank gateway
     *
     * @return mixed
     */
    public function handle_request()
    {
        // Reset result array
        $this->_result = [];
        foreach ($this->returnedVarsName as $name) {
            ${$name} = isset($_GET[$name]) ? $this->_escape_data($_GET[$name]) : null;
            $this->_result[$name] = ${$name};
        }

        return $this;
    }

    /**
     * Create request by send a request to bank gateway
     *
     * @param $data
     * @return PaymentZarinPal
     */
    public function create_request($data)
    {
        if (!isset($data[$this->MerchantIDStr])) {
            $data[$this->MerchantIDStr] = $this->_parameters[$this->MerchantIDStr];
        }
        var_dump($data);
        $this->_result = $this->_client->PaymentRequest($data);

        return $this;
    }

    /**
     * Use this method to verify created request, after <i>handle_request</i> method
     *
     * @param null $amount
     * @return object|string
     * @throws PaymentException
     */
    public function verify_request($amount = null)
    {
        if(!empty($amount)) {
            $this->_parameters[$this->amountStr] = $amount;
        }
        if (!isset($this->_parameters[$this->amountStr]) || !is_numeric($this->_parameters[$this->amountStr])) {
            throw new PaymentException('مبلغی برای احراز عملیات بانکی تعریف نشده است.', self::BANK_ERROR_UNDEFINED_AMOUNT);
        }
        if (!isset($this->_result[self::PAYMENT_RETURNED_AUTHORITY_ZARINPAL])) {
            throw new PaymentException('Authority برای احراز عملیات بانکی تعریف نشده است.', self::BANK_ERROR_UNDEFINED_AUTHORITY);
        }

        if ($this->_result[self::PAYMENT_RETURNED_STATUS_ZARINPAL] == 'OK') {
            $result = $this->_client->PaymentVerification([
                'MerchantID' => $this->_parameters[$this->MerchantIDStr],
                'Authority' => $this->_result[self::PAYMENT_RETURNED_AUTHORITY_ZARINPAL],
                'Amount' => $this->_parameters[$this->amountStr],
            ]);

            if ($result->Status == self::PAYMENT_STATUS_OK_ZARINPAL) {
                $this->_parameters[$this->statusStr] = self::PAYMENT_TRANSACTION_SUCCESS_ZARINPAL;
            } else if ($result->Status == self::PAYMENT_TRANSACTION_DUPLICATE_ZARINPAL) {
                $this->_parameters[$this->statusStr] = self::PAYMENT_TRANSACTION_DUPLICATE_ZARINPAL;
            } else {
                $this->_parameters[$this->statusStr] = self::PAYMENT_TRANSACTION_FAILED_ZARINPAL;
            }
        } else {
            $result = 'تراکنش توسط کاربر لغو شد.';
            $this->_parameters[$this->statusStr] = self::PAYMENT_TRANSACTION_CANCELED_ZARINPAL;
        }

        return $result;
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
     * @return string|bool - return <b>string</b> if there is message otherwise return <b>false</b>
     */
    public function get_message($code)
    {
        if (isset($code) && key_exists($code, $this->_statusArr)) {
            return $this->_statusArr[$code];
        }
        return false;
    }
}
