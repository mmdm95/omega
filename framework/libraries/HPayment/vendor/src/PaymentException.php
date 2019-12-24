<?php

namespace HPayment;

defined('BASE_PATH') OR exit('No direct script access allowed');

class PaymentException extends \Exception
{
    /**
     * Magic to string method
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
