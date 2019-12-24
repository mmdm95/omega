<?php

namespace HPayment;

use HPayment\PaymentClasses as pc;

defined('BASE_PATH') OR exit('No direct script access allowed');

class PaymentFactory
{
    // Payment class(es) constant(s)
    const BANK_TYPE_IDPAY = 1;
    const BANK_TYPE_MABNA = 2;
    const BANK_TYPE_ZARINPAL = 3;

    /**
     * Factory array to check if passed constant to constructor of class is valid or not
     * @var array
     */
    static private $factoryArr = [
        self::BANK_TYPE_IDPAY => 'IDPay',
        self::BANK_TYPE_MABNA => 'Mabna',
        self::BANK_TYPE_ZARINPAL => 'ZarinPal'
    ];

    // Error code(s) for factory
    const BANK_ERROR_GATEWAY_IS_INVALID = 1000;
    const BANK_ERROR_UNKNOWN_SUBCLASS = 1001;

    /**
     * Do no instantiate the PaymentFactory just use as static class
     * PaymentFactory constructor.
     */
    private function __construct()
    {
    }

    /**
     * Get instance of wanted bank class with bank type [BANK_TYPE_*] constant(s)
     *
     * @param $factory
     * @param mixed $data
     * @return pc\PaymentIDPay|pc\PaymentMabna|pc\PaymentZarinPal
     * @throws PaymentException
     */
    static public function get_instance($factory, ...$data)
    {
        if (!isset($factory) || !key_exists($factory, self::$factoryArr)) {
            throw new PaymentException('لطفا درگاه بانک خود را مشخص کنید. درگاه وارد شده نامعتبر است!', self::BANK_ERROR_GATEWAY_IS_INVALID);
        }

        $className =  __NAMESPACE__ . '\\PaymentClasses\\Payment' . self::$factoryArr[$factory];
        if (!class_exists($className)) {
            throw new PaymentException('کلاس برای درگاه وارد شده وجود ندارد. درگاه ساخته نشد!', self::BANK_ERROR_UNKNOWN_SUBCLASS);
        }

        return new $className(...$data);
    }

    /**
     * Do no clone the PaymentFactory just use as static class
     */
    private function __clone()
    {
    }
}
