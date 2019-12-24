<?php

namespace HSMS;
defined('BASE_PATH') OR exit('No direct script access allowed');

interface smsInterface
{
    /**
     * set sms target number(s)
     *
     * @param string|array $numbers
     * @return mixed
     */
    public function set_numbers($numbers);

    /**
     * set the text message want to sent to user(s)
     *
     * @param string $text
     * @return mixed
     */
    public function body($text);

    /**
     * Send sms to wanted numbers,
     * note: set numbers and text body before this function
     *
     * @return mixed
     * <p>return true if succeed otherwise return false</p>
     * <p>use get_error function to see error(s) on false</p>
     */
    public function send();

    /**
     * get account remain credit from SMS panel
     *
     * @return mixed
     */
    public function get_credit();

    /**
     * get status in associative array that <b>key</b> is status code and <b>value</b> is status message
     *
     * @return array
     */
    public function get_status();
}