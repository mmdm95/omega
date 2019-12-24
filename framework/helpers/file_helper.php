<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

/**
 * Read json file
 *
 * @param $file
 * @return mixed
 */
if(!function_exists('read_json')) {
    function read_json($file)
    {
        $data = file_get_contents($file);
        $json = json_decode($data, true);

        return $json;
    }
}

/**
 * Create/Rewrite json file
 *
 * @param $file
 * @param $obj
 * @return bool
 */
if(!function_exists('write_json')) {
    function write_json($file, $obj)
    {
        $fp = fopen($file, 'w');
        fwrite($fp, json_encode($obj));
        return fclose($fp);
    }
}
