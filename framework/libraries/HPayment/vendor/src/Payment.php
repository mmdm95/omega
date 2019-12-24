<?php

namespace HPayment;

defined('BASE_PATH') OR exit('No direct script access allowed');

abstract class Payment
{
    // Error code constant(s)
    const BANK_ERROR_BAD_API_KEY = 1200;
    const BANK_ERROR_UNDEFINED_URL = 1201;
    const BANK_ERROR_UNDEFINED_METHOD = 1202;
    const BANK_ERROR_UNDEFINED_AMOUNT = 1203;
    const BANK_ERROR_UNDEFINED_AUTHORITY = 1204;

    // Method constants
    const PAYMENT_METHOD_POST = 'POST';
    const PAYMENT_METHOD_GET = 'GET';
    const PAYMENT_METHOD_CONNECT = 'CONNECT';

    //********************************
    //******* IDPay constants ********
    //********************************
    // mode constant(s)
    const PAYMENT_MODE_DEVELOPMENT_IDPAY = 1;
    const PAYMENT_MODE_PRODUCTION_IDPAY = 2;
    // url constant(s)
    const PAYMENT_URL_PAYMENT_IDPAY = 1;
    const PAYMENT_URL_VERIFY_IDPAY = 2;
    const PAYMENT_URL_INQUIRY_IDPAY = 3;
    // status constant(s)
    const PAYMENT_STATUS_REQUEST_IDPAY = 1;
    const PAYMENT_STATUS_VERIFY_IDPAY = 2;
    //-----
    const PAYMENT_STATUS_OK_IDPAY = 100;
    const PAYMENT_STATUS_WAIT_IDPAY = 10;
    const PAYMENT_STATUS_DUPLICATE_IDPAY = 101;
    const PAYMENT_STATUS_FAILED_IDPAY = 1;

    //********************************
    //***** Mabna Card constants *****
    //********************************
    const PAYMENT_TRANSACTION_SUCCESS_MABNA = 0;
    const PAYMENT_TRANSACTION_CANCELED_MABNA = -1;
    const PAYMENT_TRANSACTION_TIMEOUT_MABNA = -2;
    // url constant(s)
    const PAYMENT_URL_PAYMENT_MABNA = 1;
    const PAYMENT_URL_BILL_MABNA = 2;
    const PAYMENT_URL_BATCH_BILL_MABNA = 3;
    const PAYMENT_URL_CHARGE_MABNA = 4;
    const PAYMENT_URL_MOBILE_PAYMENT_MABNA = 5;
    const PAYMENT_URL_MOBILE_BILL_MABNA = 6;
    const PAYMENT_URL_MOBILE_BATCH_BILL_MABNA = 7;
    const PAYMENT_URL_MOBILE_CHARGE_MABNA = 8;
    const PAYMENT_URL_VERIFY_MABNA = 9;
    // status constant(s)
    const PAYMENT_STATUS_REQUEST_MABNA = 1;
    const PAYMENT_STATUS_VERIFY_MABNA = 2;
    //-----
    const PAYMENT_STATUS_OK_MABNA = 100;
    const PAYMENT_STATUS_FAILED_MABNA = -1;
    const PAYMENT_STATUS_DUPLICATE_MABNA = 101;

    //********************************
    //****** ZarinPal constants ******
    //********************************
    const PAYMENT_TRANSACTION_SUCCESS_ZARINPAL = 1;
    const PAYMENT_TRANSACTION_FAILED_ZARINPAL = 2;
    const PAYMENT_TRANSACTION_DUPLICATE_ZARINPAL = 4;
    const PAYMENT_TRANSACTION_CANCELED_ZARINPAL = 8;
    // gateway returned GET constant(s)
    const PAYMENT_RETURNED_AUTHORITY_ZARINPAL = 'Authority';
    const PAYMENT_RETURNED_STATUS_ZARINPAL = 'Status';
    // url constant(s)
    const PAYMENT_URL_SOAP_ZARINPAL = 1;
    const PAYMENT_URL_PAYMENT_ZARINPAL = 2;
    // status constant(s)
    const PAYMENT_STATUS_OK_ZARINPAL = 100;
    const PAYMENT_STATUS_FAILED_ZARINPAL = -22;
    const PAYMENT_STATUS_DUPLICATE_ZARINPAL = 101;

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
    protected $_statusArr = [];

    /**
     * Store current status of payment/advice/etc. as an array.
     * Exp. array(
     *  'code' => 100,
     *  'message' => 'Payment complete.'
     * )
     * @var
     */
    protected $_status;

    /**
     * Parameters for send/get/verify that must set for bank gateway
     * @var array
     */
    protected $_parameters = [];

    /**
     * Store result that come from request/verify
     * @var array
     */
    protected $_result = [];

    /**
     * Magic get method to get specific parameter value
     *
     * @param $propertyName
     * @return mixed|null
     */
    public function __get($propertyName)
    {
        if (array_key_exists($propertyName, $this->_parameters)) {
            return $this->_parameters[$propertyName];
        }
        return null;
    }

    /**
     * Magic set method to set specific value to parameters array
     *
     * @param $propertyName
     * @param $propertyValue
     */
    public function __set($propertyName, $propertyValue)
    {
        $this->_parameters[$propertyName] = $propertyValue;
    }

    /**
     * Handle requested operation that come from bank gateway
     *
     * @return mixed
     */
    abstract public function handle_request();

    /**
     * Create request by send a request to bank gateway
     *
     * @param $data
     * @return mixed
     */
    abstract public function create_request($data);

    /**
     * Send advice to bank gateway to complete payment transaction
     *
     * @param $data
     * @return mixed
     */
    public function send_advice($data)
    {
        return null;
    }

    /**
     * Escape sent data from bank gateway to protect returned data
     *
     * @param $data
     * @return string
     */
    final protected function _escape_data($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
