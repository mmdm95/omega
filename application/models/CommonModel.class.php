<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class CommonModel
{
    const UPPER_CHARS = 0x1;
    const LOWER_CHARS = 0x2;
    const DIGITS = 0x4;

    protected $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function generate_random_unique_code($table, $codeColumn, $preFix = '', $minLength = 8, $retryThreshold = 15, $extendThreshold = 10, $typeFlag = self::UPPER_CHARS | self::LOWER_CHARS | self::DIGITS)
    {
        $characters = '';
        if($typeFlag & self::DIGITS) {
            $characters .= '0123456789';
        }
        if($typeFlag & self::LOWER_CHARS) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if($typeFlag & self::UPPER_CHARS) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        $retry = 0;
        $counter = 0;
        $length = $minLength;
        while ($counter <= $extendThreshold) {
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            if (!$this->model->is_exist($table, "{$codeColumn}=:code", ['code' => $preFix . $randomString])) {
                break;
            } else if($retry > $retryThreshold) {
                if ($counter == $extendThreshold) {
                    $length++;
                    $counter = 0;
                } else {
                    $counter++;
                }
            } else {
                $retry++;
            }
        }

        return $randomString;
    }
}
