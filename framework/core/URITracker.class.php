<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class URITracker
{
    protected static $trackStr = 'uri_tracks';
    public static $count = 10;

    public static function set_uri_count($num)
    {
        if (is_numeric($num)) {
            self::$count = (int)$num;
        }
    }

    public static function add_uri($url)
    {
        // If uri tracks not set or not array
        if (!isset($_SESSION[self::$trackStr]) || !is_array($_SESSION[self::$trackStr])) $_SESSION[self::$trackStr] = [];

        $removedUri = getConfig('trackConfig');
        $removedUri = isset($removedUri['remove_uri_actions']) && is_array($removedUri['remove_uri_actions']) ? $removedUri['remove_uri_actions'] : [];
        foreach ($removedUri as $item) {
            if (strpos(strtolower($url), strtolower($item)) !== false) {
                return false;
            }
        }

        $trackerCount = count($_SESSION[self::$trackStr]);
        if ($trackerCount >= self::$count) {
            array_shift($_SESSION[self::$trackStr]);
        }
        $_SESSION[self::$trackStr][] = $url;

        return true;
    }

    public static function remove_uri($index = 0)
    {
        // If uri tracks not set or not array
        if (!isset($_SESSION[self::$trackStr]) || !is_array($_SESSION[self::$trackStr])) $_SESSION[self::$trackStr] = [];

        $trackerCount = count($_SESSION[self::$trackStr]);
        if ($trackerCount == 0) return;

        $index = is_numeric($index) ? (int)$index : 0;
        while($index < 0) {
            $index = $trackerCount + $index;
        }
        $index = (int)$index % (int)$trackerCount;

        unset($_SESSION[self::$trackStr][$index]);
    }

    public static function remove_last_uri()
    {
        self::remove_uri(-1);
    }

    public static function get_uri($index = 0)
    {
        // If uri tracks not set or not array
        if (!isset($_SESSION[self::$trackStr]) || !is_array($_SESSION[self::$trackStr])) $_SESSION[self::$trackStr] = [];

        $trackerCount = count($_SESSION[self::$trackStr]);
        if ($trackerCount == 0) return false;

        $index = is_numeric($index) ? (int)$index : 0;
        while($index < 0) {
            $index = $trackerCount + $index;
        }
        $index = (int)$index % (int)$trackerCount;
        return $_SESSION[self::$trackStr][$index];
    }

    public static function get_tracks()
    {
        // If uri tracks not set or not array
        if (!isset($_SESSION[self::$trackStr]) || !is_array($_SESSION[self::$trackStr])) $_SESSION[self::$trackStr] = [];

        return $_SESSION[self::$trackStr];
    }

    public static function get_last_uri()
    {
        return self::get_uri(-1);
    }

    public static function reset_tracks()
    {
        $_SESSION[self::$trackStr] = [];
    }
}
