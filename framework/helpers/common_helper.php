<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

const GRS_NUMBER = 0x1;
const GRS_LOWER_CHAR = 0x2;
const GRS_UPPER_CHAR = 0x4;

const ED_ENCRYPT = 'encrypt';
const ED_DECRYPT = 'decrypt';

const Persian_Numbers = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
const English_Numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

if (!function_exists('generateRandomString')) {
    /**
     * Generate random string/int/mixed
     *
     * @param int $length | Default length = 6
     * @param int $kind
     * @return string
     * @throws Exception
     */
    function generateRandomString($length = 6, $kind = GRS_NUMBER | GRS_LOWER_CHAR | GRS_UPPER_CHAR)
    {
        $characters = '';
        if ($kind & GRS_NUMBER) {
            $characters .= '0123456789';
        }
        if ($kind & GRS_LOWER_CHAR) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($kind & GRS_UPPER_CHAR) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);

        if ($charactersLength == 0) {
            throw new Exception('پارامترهای ارسالی را بررسی نمایید!');
        }

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

if (!function_exists('array_merge_recursive_distinct')) {
    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }
}

/**
 * @see https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
 */
if (!function_exists('createCaptcha')) {
    function createCaptcha($code = null)
    {
        if(empty($code)) {
            $code = generateRandomString(6, GRS_NUMBER | GRS_LOWER_CHAR);
        }

        $image = imagecreatetruecolor(170, 40);

        imageantialias($image, true);

        $colors = [];

        $red = rand(125, 175);
        $green = rand(125, 175);
        $blue = rand(125, 175);

        for($i = 0; $i < 5; $i++) {
            $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
        }

        imagefill($image, 0, 0, $colors[0]);

        for($i = 0; $i < 10; $i++) {
            imagesetthickness($image, rand(2, 10));
            $line_color = $colors[rand(1, 4)];
            imagerectangle($image, rand(-10, 160), rand(-10, 10), rand(-10, 160), rand(40, 60), $line_color);
        }

        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        $textcolors = [$black, $white];

        $fonts = [ROOT . FONTS_PATH . 'IRANSansWeb.ttf'];

        $string_length = mb_strlen($code);
        $captcha_string = $code;

        $captchaSesName = getConfig('config');
        if(!$captchaSesName['captcha_session_name']) {
            $captchaSesName = 'captcha_text';
        } else {
            $captchaSesName = $captchaSesName['captcha_session_name'];
        }
        $_SESSION[$captchaSesName] = encryption_decryption(ED_ENCRYPT, $captcha_string);

        for($i = 0; $i < $string_length; $i++) {
            $letter_space = 140/$string_length;
            $initial = 15;

            imagettftext($image, 20, rand(-15, 15), $initial + $i*$letter_space, rand(25, 35), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
        }

        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    }
}

if (!function_exists('convertNumbersToPersian')) {
    /**
     * Convert all persian numbers to english/english number to persian [with set $reverse to <b>true</b>]
     * @param $str
     * @param bool $reverse
     * @return mixed
     */
    function convertNumbersToPersian($str, $reverse = false)
    {
        if (is_array($str)) {
            $newArr = [];
            foreach ($str as $k => $v) {
                $newArr[$k] = convertNumbersToPersian($str[$k], $reverse);
            }
            return $newArr;
        }

        if ($reverse) {
            return str_replace(Persian_Numbers, English_Numbers, $str);
        }
        return str_replace(English_Numbers, Persian_Numbers, $str);
    }
}

if (!function_exists('findQueryKeyVal')) {
    /**
     * Find a specific value in a query key=>value string
     * @param $query
     * @param $findKey
     * @param string $kvSep
     * @param string $partSep
     * @return null
     */
    function findQueryKeyVal($query, $findKey, $kvSep = '=', $partSep = '&')
    {
        $kvSepArr = [];

        $partSepArr = explode($partSep, $query);
        foreach ($partSepArr as $k => $v) {
            $kvSepArr[$k] = explode($kvSep, $v);
        };

        foreach ($kvSepArr as $key => $val) {
            if ($val[0] === $findKey) {
                return $val[1];
            }
        }
        return null;
    }
}

if (!function_exists('h_array_search')) {
    /**
     * Function that search in multidimensional array for specific item with a key and wanted value
     *
     * @see https://stackoverflow.com/questions/1019076/how-to-search-by-key-value-in-a-multidimensional-array-in-php for more information
     * @param $array
     * @param $key
     * @param $value
     * @return array
     */
    function h_array_search($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, h_array_search($subarray, $key, $value));
            }
        }

        return $results;
    }
}

if (!function_exists('array_group_by')) {
    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param string $key Property to sort by.
     * @param array $data Array that stores multiple associative arrays.
     * @param array $wantedKeys Array of columns that you need your new grouped array have.
     * @param bool $reverseWantedKeys
     * @return array
     */
    function array_group_by($key, $data, $wantedKeys = [], $reverseWantedKeys = false)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $newVal = $val;
                if (is_array($wantedKeys) && count($wantedKeys)) {
                    if (!($reverseWantedKeys === true)) {
                        $newVal = [];
                    }
                    foreach ($wantedKeys as $wantedKey) {
                        if (array_key_exists($wantedKey, $val)) {
                            if ($reverseWantedKeys === true) {
                                unset($newVal[$wantedKey]);
                            } else {
                                $newVal[$wantedKey] = $val[$wantedKey];
                            }
                        }
                    }
                }
                $result[$val[$key]][] = $newVal;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}

if (!function_exists('encryption_decryption')) {
    function encryption_decryption($action, $data)
    {
        if (empty($data)) return false;

        $first_key = base64_decode(MAIN_KEY);
        $second_key = base64_decode(ASSURED_KEY);

        if ($action == 'encrypt') {
            $method = "aes-256-cbc";
            $iv_length = openssl_cipher_iv_length($method);
            $iv = openssl_random_pseudo_bytes($iv_length);

            $first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, $iv);
            $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

            $output = base64_encode($iv . $second_encrypted . $first_encrypted);
            return $output;
        } else if ($action == 'decrypt') {
            $mix = base64_decode($data);

            $method = "aes-256-cbc";
            $iv_length = openssl_cipher_iv_length($method);

            $iv = substr($mix, 0, $iv_length);
            $second_encrypted = substr($mix, $iv_length, 64);
            $first_encrypted = substr($mix, $iv_length + 64);

            $data = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);
            $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

            if (hash_equals($second_encrypted, $second_encrypted_new))
                return $data;

            return false;
        }

        return false;
    }
}

if (!function_exists('message')) {
    function message($type, $code, $msg)
    {
        http_response_code($code);
        echo json_encode([$type => ['code' => intval($code), 'msg' => $msg]]);
        exit;
    }
}

if (!function_exists('titleMaker')) {
    function titleMaker($delimiter, ...$_)
    {
        return implode($delimiter, $_);
    }
}

if (!function_exists('url_title')) {
    /**
     * This code is directly from CI framework (Thanks to them a lot)
     * Create URL Title
     *
     * Takes a "title" string as input and creates a
     * human-friendly URL string with a "separator" string
     * as the word separator.
     *
     * @param    string $str Input string
     * @param    string $separator Word separator
     *            (usually '-' or '_')
     * @param    bool $lowercase Whether to transform the output string to lowercase
     * @return    string
     */
    function url_title($str, $separator = '-', $lowercase = FALSE)
    {
        $q_separator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;' => '',
            '[^\w\d _-]' => '',
            '\s+' => $separator,
            '(' . $q_separator . ')+' => $separator
        );

        $str = strip_tags($str);
        foreach ($trans as $key => $val) {
            $str = preg_replace('#' . $key . '#i' . (UTF8_ENABLED ? 'u' : ''), $val, $str);
        }

        if ($lowercase === TRUE) {
            $str = strtolower($str);
        }

        return trim(trim($str, $separator));
    }
}

if (!function_exists('character_limiter')) {
    /**
     * This code is directly from CI framework (Thanks to them a lot)
     * Character Limiter
     *
     * Limits the string based on the character count.  Preserves complete words
     * so the character count may not be exactly as specified.
     *
     * @param    string
     * @param    int
     * @param    string    the end character. Usually an ellipsis
     * @return    string
     */
    function character_limiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (mb_strlen($str) < $n) {
            return $str;
        }

// a bit complicated, but faster than preg_replace with \s+
        $str = preg_replace('/ {2,}/', ' ', str_replace(array("\r", "\n", "\t", "\v", "\f"), ' ', $str));

        if (mb_strlen($str) <= $n) {
            return $str;
        }

        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';

            if (mb_strlen($out) >= $n) {
                $out = trim($out);
                return (mb_strlen($out) === mb_strlen($str)) ? $out : $out . $end_char;
            }
        }
    }
}

if (!function_exists('is_ajax')) {
//Function to check if the request is an AJAX request
    function is_ajax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

if (!function_exists('getConfig')) {
    function getConfig($name)
    {
        return include CONFIG_PATH . "$name.php";
    }
}

if (!function_exists('isValidTimeStamp')) {
    function isValidTimeStamp($timestamp)
    {
        return ((string)(int)$timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }
}

if (!function_exists('isValidHexColor')) {
    function isValidHexColor($color)
    {
        return preg_match("/#([a-f0-9]{3}){1,2}\b/i", $color);
    }
}

if (!function_exists('http_response_code')) {
    function http_response_code($code = NULL)
    {

        if ($code !== NULL) {

            switch ($code) {
                case 100:
                    $text = 'Continue';
                    break;
                case 101:
                    $text = 'Switching Protocols';
                    break;
                case 200:
                    $text = 'OK';
                    break;
                case 201:
                    $text = 'Created';
                    break;
                case 202:
                    $text = 'Accepted';
                    break;
                case 203:
                    $text = 'Non-Authoritative Information';
                    break;
                case 204:
                    $text = 'No Content';
                    break;
                case 205:
                    $text = 'Reset Content';
                    break;
                case 206:
                    $text = 'Partial Content';
                    break;
                case 300:
                    $text = 'Multiple Choices';
                    break;
                case 301:
                    $text = 'Moved Permanently';
                    break;
                case 302:
                    $text = 'Moved Temporarily';
                    break;
                case 303:
                    $text = 'See Other';
                    break;
                case 304:
                    $text = 'Not Modified';
                    break;
                case 305:
                    $text = 'Use Proxy';
                    break;
                case 400:
                    $text = 'Bad Request';
                    break;
                case 401:
                    $text = 'Unauthorized';
                    break;
                case 402:
                    $text = 'Payment Required';
                    break;
                case 403:
                    $text = 'Forbidden';
                    break;
                case 404:
                    $text = 'Not Found';
                    break;
                case 405:
                    $text = 'Method Not Allowed';
                    break;
                case 406:
                    $text = 'Not Acceptable';
                    break;
                case 407:
                    $text = 'Proxy Authentication Required';
                    break;
                case 408:
                    $text = 'Request Time-out';
                    break;
                case 409:
                    $text = 'Conflict';
                    break;
                case 410:
                    $text = 'Gone';
                    break;
                case 411:
                    $text = 'Length Required';
                    break;
                case 412:
                    $text = 'Precondition Failed';
                    break;
                case 413:
                    $text = 'Request Entity Too Large';
                    break;
                case 414:
                    $text = 'Request-URI Too Large';
                    break;
                case 415:
                    $text = 'Unsupported Media Type';
                    break;
                case 500:
                    $text = 'Internal Server Error';
                    break;
                case 501:
                    $text = 'Not Implemented';
                    break;
                case 502:
                    $text = 'Bad Gateway';
                    break;
                case 503:
                    $text = 'Service Unavailable';
                    break;
                case 504:
                    $text = 'Gateway Time-out';
                    break;
                case 505:
                    $text = 'HTTP Version not supported';
                    break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            header($protocol . ' ' . $code . ' ' . $text);

            $GLOBALS['http_response_code'] = $code;

        } else {

            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

        }

        return $code;

    }
}

if (!function_exists('get_client_ip_env')) {
// Function to get the client ip address
    function get_client_ip_env()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}

if (!function_exists('get_client_ip_server')) {
// Function to get the client ip address
    function get_client_ip_server()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}

if (!function_exists('set_value')) {
    function set_value($variable, $compareTo = null, $prefer = null, $default = '', $comparisonOperand = '!==')
    {
        if (empty($default)) $default = '';
        if (empty($comparisonOperand)) $comparisonOperand = '!==';

// Return default if variable is not set or empty
        if (!isset($variable) || empty($variable)) return $default;

        if ((empty($compareTo) && $compareTo === '') || !empty($compareTo)) {
            switch ($comparisonOperand) {
                case '===':
                    return isset($variable) && $variable === $compareTo ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
                case '!==':
                    return isset($variable) && $variable !== $compareTo ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
                case '==':
                    return isset($variable) && $variable == $compareTo ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
                case '!=':
                    return isset($variable) && $variable != $compareTo ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
                case '>=':
                    return isset($variable) && $variable >= $compareTo ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
                case '<=':
                    return isset($variable) && $variable <= $compareTo ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
                case '>':
                    return isset($variable) && $variable > $compareTo ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
                case '<':
                    return isset($variable) && $variable < $compareTo ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
            }
        }
        return isset($variable) ? (empty($prefer) && $prefer !== '' ? $variable : $prefer) : $default;
    }
}

if (!function_exists('asset_url')) {
    function asset_url($url = '', $removePublic = false, $defaultSeparator = DS)
    {
        $url = trim(trim($url, '/'), '\\');
        if (empty($url)) return ASSET_ROOT;
        if ($removePublic) return trim(trim(str_replace('public', '', ASSET_ROOT), '\\'), '/') . '/' . $url;
        return ASSET_ROOT . str_replace('/', $defaultSeparator, str_replace('\\', $defaultSeparator, $url));
    }
}

if (!function_exists('base_url')) {
    function base_url($url = '', $defaultSeparator = DS)
    {
        $url = trim(trim($url, '/'), '\\');
        if (empty($url)) return BASE_URL;
        return BASE_URL . str_replace('/', $defaultSeparator, str_replace('\\', $defaultSeparator, $url));
    }
}

if (!function_exists('dir_entries')) {
    function dir_entries($path)
    {
        $tmp = [];

        if ($handle = opendir($path)) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {

                    $tmp[] = $entry;
                }
            }

            closedir($handle);
        }

        return $tmp;
    }
}
