<?php

namespace HSMS;
use SoapClient;

defined('BASE_PATH') OR exit('No direct script access allowed');

ini_set("soap.wsdl_cache_enabled", "0");

spl_autoload_register(function ($class_name) {
    $fileToTest = str_replace('\\', DS, LIB_PATH . $class_name . '.class.php');
    $file = str_replace('\\', DS, LIB_PATH . $class_name . '.php');
    if (file_exists($fileToTest)) {
        include $fileToTest;
    } else if (file_exists($file)) {
        include $file;
    }
});

class rohamSMS implements smsInterface
{
    /**
     * soap object
     * @var SoapClient
     */
    private $_smsClient;

    /**
     * current status
     * @var array
     */
    private $_status = [
        'code' => 442,
        'message' => 'خطای تعریف نشده'
    ];

    /**
     * error/success codes and messages
     * @var array
     */
    private $_statusArr = [
        'unknown' => [
            442 => 'خطای تعریف نشده'
        ],
        'send' => [
            0 => 'ارسال با موفقیت انجام شد.',
            1 => 'نام کاربر یا کلمه عبور نامعتبر می باشد.',
            2 => 'کاربر مسدود شده است.',
            3 => 'شماره فرستنده نامعتبر است.',
            4 => 'محدودیت در ارسال روزانه',
            5 => 'تعداد گیرندگان حداکثر ۱۰۰ شماره می باشد.',
            6 => 'خط فرسنتده غیرفعال است.',
            7 => 'متن پیامک شامل کلمات فیلتر شده است.',
            8 => 'اعتبار کافی نیست.',
            9 => 'سامانه در حال بروز رسانی می باشد.',
            10 => 'وب سرویس غیرفعال است.',
        ],
        'getBatch' => [
            -1 => 'نام کاربری و رمز عبور صحیح نمی باشد.',
            -2 => 'ارسال با مقدار شناسه batchSmsId وجود ندارد.',
        ],
        'delivery' => [
            -5 => 'برای گرفتن گزارش تحویل حداقل یک دقیقه بعد از ارسال اقدام نمائید.',
            -4 => 'به علت اینکه پیام در صف ارسال مخابرات می باشد، امکان گرفتن گزارش تحویل وجود ندارد.',
            -3 => 'به علت اینکه مهلت یک هفته ای گرفتن گزارش پایان یافته است، امکان گرفتن گزارش تحویل وجود ندارد.',
            -2 => 'پیام با این کد وجود ندارد.(batchSmsId نامعتبر است.)',
            -1 => 'خطا در ارتباط با سرویس دهنده',
            0 => 'ارسال شده به مخابرات',
            1 => 'رسیده به گوشی',
            2 => 'نرسیده به گوشی',
            3 => 'خطای مخابراتی',
            4 => 'خطای نامشخص',
            5 => 'رسیده به مخابرات',
            6 => 'نرسیده به مخابرات',
            7 => 'مسدود شده توسط مقصد',
            8 => 'نامشخص',
            9 => 'مخابرات پیام را مردود اعلام کرد',
            10 => 'کنسل شده توسط اپراتور',
            11 => 'ارسال نشده',
        ],
        'credit' => [
            -1 => 'نام کاربری و رمز عبور صحیح نمی باشد.',
            -2 => 'کاربر غیرفعال می باشد.'
        ]
    ];

    /**
     * web service url
     * @var string
     */
    private $_webServiceAddr = 'http://payamak-service.ir/SendService.svc?wsdl';

    /**
     * base sms panel configuration
     * @var array
     */
    private $_config = [
        'userName' => 'shirazmarketing',
        'password' => '2280155346',
//        'userName' => 'hrdashti',
//        'password' => '1122',
    ];

    /**
     * sms parameters for send or get
     * @var array
     */
    private $_parameters = [
        'isFlash' => false,
        'fromNumber' => '10000100000',
//        'fromNumber' => '5000200022',
//        'fromNumber' => '210001010101010',
    ];

    /**
     * store to numbers from _parameters variable for future use
     * @var array
     */
    private $_tmpToNumbers = [];

    private $_tmpSendStatus = [
        'recId' => array(),
        'status' => array()
    ];

    public function __construct($config = [], $webServiceAddr = '')
    {
        if (!empty($webServiceAddr)) {
            $this->_webServiceAddr = $webServiceAddr;
        }
        if (!empty($config)) {
            $this->_config = array_merge($this->_config, $config);
        }

        // merge parameters and base config to parameters array
        $this->_parameters = array_merge_recursive_distinct($this->_parameters, $this->_config);

        // new instant of soap object
        $this->_smsClient = new SoapClient($this->_webServiceAddr, array('encoding' => 'UTF-8'));
    }

    public function __get($propertyName)
    {
        if (array_key_exists($propertyName, $this->_parameters)) {
            return $this->_parameters[$propertyName];
        }
        return null;
    }

    public function __set($propertyName, $propertyValue)
    {
        $this->_parameters[$propertyName] = $propertyValue;
    }

    /**
     * set sms target number(s)
     *
     * @param string|array $numbers
     * @return rohamSMS
     * @throws SMSException
     */
    public function set_numbers($numbers)
    {
        if (!is_string($numbers) && !is_array($numbers)) throw new SMSException('ورودی باید از نوع آرایه یا رشته باشد.');
        $numbers = is_string($numbers) ? [$numbers] : $numbers;
        // Validate number(s) -> just send sms to valid number(s)
        $numbers = $this->_validate_numbers($numbers);
        // Throw exception when we have no number(s)
        if (!count($numbers)) {
            throw new SMSException('شماره(های) انتخاب شده برای ارسال پیامک، نامعتبر است!');
        }
        // Add unique number(s) to global parameter
        $this->_parameters['toNumbers'] = array_unique(array_merge($this->_parameters['toNumbers'] ?? [], $numbers));
        // Store numbers in temporary to numbers for future use
        $this->_tmpToNumbers = $this->_parameters['toNumbers'];

        return $this;
    }

    /**
     * set the text message want to sent to user(s)
     *
     * @param string $text
     * @return rohamSMS
     * @throws SMSException
     */
    public function body($text)
    {
        if (!is_string($text)) throw new SMSException('متن باید از نوع رشته باشد.');
        $this->_parameters['messageContent'] = $text;
        return $this;
    }

    /**
     * Send sms to wanted numbers,
     * note: set numbers and text body before this function
     *
     * @return bool
     * <p>return true if succeed otherwise return false</p>
     * <p>use get_error function to see error(s) on false</p>
     * @throws SMSException
     */
    public function send()
    {
        // Check if have any number(s)
        if (!$this->_parameters['toNumbers']) {
            throw new SMSException('ابتدا شماره(های) مقصد برای ارسال پیامک را مشخص کنید.');
        }
        // Send sms to number(s)
        $successCount = 0;
        $mustSlice = ceil(count($this->_parameters['toNumbers']) / 100);
        for ($i = 0; $i < $mustSlice; $i++) {
            $recId = array();
            $status = array();
            $this->_parameters['recId'] = &$recId;
//            $this->_parameters['status'] = &$status;

            // Slice 100 numbers each time
            $this->_parameters['toNumbers'] = array_slice($this->_parameters['toNumbers'], $i * 100, 100);
            // Send sms functionality
            $sendRes = $this->_smsClient->SendSMS($this->_parameters)->SendSMSResult;
            if ($sendRes == 0) {
                $successCount++;
            }
            if ($mustSlice == 1) {
                $this->_status = [
                    'code' => $sendRes,
                    'message' => $this->_statusArr['send'][$sendRes]
                ];
            } else {
                $this->_status[] = [
                    'code' => $sendRes,
                    'message' => $this->_statusArr['send'][$sendRes]
                ];
            }

            $this->_tmpSendStatus['recId'] = array_merge($this->_tmpSendStatus['recId'], $recId);
//            $this->_tmpSendStatus['status'] = array_merge($this->_tmpSendStatus['status'], $status);
        }

        return $successCount == $mustSlice;
    }

    /**
     * get account remain credit from SMS panel
     *
     * @return mixed
     */
    public function get_credit()
    {
        // Get base parameters for get credit
        $baseParams = array_intersect_key($this->_parameters, $this->_config);

        // Get sms credit functionality
        $res = $this->_smsClient->GetCredit($baseParams)->GetCreditResult;
        // Set status just on error (don't have success status code)
        if($res < 0) {
            $this->_status = [
                'code' => $res,
                'message' => $this->_statusArr['credit'][$res]
            ];
        }
        return $res;
    }

    /**
     * Get all validated numbers that set with <i>set_numbers</i> method
     *
     * @return array
     */
    public function get_numbers()
    {
        return $this->_tmpToNumbers;
    }

    /**
     * get status in associative array that <b>key</b> is status code and <b>value</b> is status message
     *
     * @return array
     */
    public function get_status()
    {
        return $this->_status;
    }

    /**
     * Get report of send refId and status
     *
     * @return array
     */
    public function get_report()
    {
        return $this->_tmpSendStatus;
    }

    protected function _validate_numbers($numbers)
    {
        $numbers = array_filter($numbers, function ($number) {
            return preg_match("/^09[0-9]{9}$/", $number);
        });
        $numbers = convertNumbersToPersian($numbers, true);
        return $numbers;
    }
}