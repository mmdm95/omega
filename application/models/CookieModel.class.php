<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class CookieModel
{
    const COOKIE_PASSWORD_HASH = 0,
        COOKIE_ENCRYPT_DECRYPT = 1;

    /**
     * Set a cookie with given parameters
     *
     * @param $name
     * @param null $value
     * @param null $expire
     * @param null $path
     * @param null $domain
     * @param null $secure
     * @param null $httpOnly
     * @param int $cryptType
     * @return bool
     */
    public function set_cookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httpOnly = null, $cryptType = self::COOKIE_ENCRYPT_DECRYPT)
    {
        $crypt = '';

        switch ($cryptType) {
            case self::COOKIE_PASSWORD_HASH:
                $crypt = password_hash($value, PASSWORD_DEFAULT);
                break;
            case self::COOKIE_ENCRYPT_DECRYPT:
                $crypt = $this->encryptData($value);
                break;
        }

        return setcookie($name, $crypt, $expire, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Check if a cookie with given name is exists,
     * then return a bool or its value
     *
     * @param $name
     * @param bool $getValue
     * @param bool $getDecryptedValue
     * @param int $cryptType
     * @return bool|string
     */
    public function is_cookie_set($name, $getValue = false, $getDecryptedValue = false, $cryptType = self::COOKIE_ENCRYPT_DECRYPT)
    {
        $decrypt = '';

        if (isset($_COOKIE[$name])) {
            if ($getValue) {
                $decrypt = $_COOKIE[$name];
                if ($getDecryptedValue && $cryptType == self::COOKIE_ENCRYPT_DECRYPT) {
                    $decrypt = $this->decryptData($decrypt);
                }
                return $decrypt;
            } else {
                return true;
            }
        }

        if ($getValue) {
            return $decrypt;
        } else {
            return false;
        }
    }

    /**
     * Check if a cookie is exists and verify it
     *
     * @param $name
     * @param $verifyValue
     * @param int $cryptType
     * @return bool
     */
    public function cookie_verify($name, $verifyValue, $cryptType = self::COOKIE_ENCRYPT_DECRYPT)
    {
        $crypt = false;
        $val = $this->is_cookie_set($name, true);

        switch ($cryptType) {
            case self::COOKIE_PASSWORD_HASH:
                if (password_verify($verifyValue, $val)) {
                    return !$crypt;
                }
                break;
            case self::COOKIE_ENCRYPT_DECRYPT:
                if ($this->decryptData($val) == $verifyValue) {
                    return !$crypt;
                }
                break;
        }

        return $crypt;
    }

    /**
     * Encrypt data with secured algorithm
     *
     * @param $data
     * @return string
     *
     */
    private function encryptData($data)
    {
        $first_key = base64_decode(MAIN_KEY);
        $second_key = base64_decode(ASSURED_KEY);

        $method = "aes-256-cbc";
        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);

        $first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, $iv);
        $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

        $output = base64_encode($iv . $second_encrypted . $first_encrypted);

        return $output;
    }

    /**
     * Decrypt the encrypted data
     *
     * @param $data
     * @return bool|string
     *
     */
    private function decryptData($data)
    {
        $first_key = base64_decode(MAIN_KEY);
        $second_key = base64_decode(ASSURED_KEY);
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
}