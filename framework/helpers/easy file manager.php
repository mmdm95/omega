<?php

////Security options
//$allow_delete = true; // Set to false to disable delete button and delete POST request.
//$allow_upload = true; // Set to true to allow upload files
//$allow_create_folder = true; // Set to false to disable folder creation
//$allow_direct_link = true; // Set to false to only allow downloads and not direct link
//$disallowed_extensions = ['php'];  // must be an array. Extensions disallowed to be uploaded
//$hidden_extensions = ['php']; // must be an array of lowercase file extensions. Extensions hidden in directory index
//$shown_extensions = ['png', 'jpg', 'jpeg', 'gif', 'xls']; // must be an array of lowercase file extensions. Extensions shown in directory index
//    $PASSWORD = '';  // Set the password, to access the file manager... (optional)

function allow_delete($bool = true)
{
    return $bool;
}

function allow_upload($bool = true)
{
    return $bool;
}

function allow_create_folder($bool = true)
{
    return $bool;
}

function allow_direct_link($bool = true)
{
    return $bool;
}

function disallowed_extensions($arr = ['php', 'html'])
{
    return $arr;
}

function allowed_extensions($arr = [])
{
    return $arr;
}

function hidden_extensions($arr = ['php', 'html'])
{
    return $arr;
}

function shown_extensions($arr = [])
{
    return $arr;
}

function rmrf($dir)
{
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file)
            rmrf("$dir/$file");
        rmdir($dir);
    } else {
        unlink($dir);
    }
}

function is_recursively_deleteable($d)
{
    $stack = [$d];
    while ($dir = array_pop($stack)) {
        if (!is_readable($dir) || !is_writable($dir))
            return false;
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) if (is_dir($file)) {
            $stack[] = "$dir/$file";
        }
    }
    return true;
}

// from: http://php.net/manual/en/function.realpath.php#84012
function get_absolute_path($path)
{
    $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    $parts = explode(DIRECTORY_SEPARATOR, $path);
    $absolutes = [];
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    return implode(DIRECTORY_SEPARATOR, $absolutes);
}

function err($code, $msg)
{
    http_response_code($code);
    echo json_encode(['error' => ['code' => intval($code), 'msg' => $msg]]);
    exit;
}

function asBytes($ini_v)
{
    $ini_v = trim($ini_v);
    $s = ['g' => 1 << 30, 'm' => 1 << 20, 'k' => 1 << 10];
    return intval($ini_v) * ($s[strtolower(substr($ini_v, -1))] ?: 1);
}

function check_file_uploaded_length ($filename)
{
    return (bool) ((mb_strlen($filename,"UTF-8") > 225) ? true : false);
}

function get_extension($path)
{
    return strtolower(pathinfo($path, PATHINFO_EXTENSION));
}

function get_base_name($path)
{
    $arr = explode('/', str_replace('\\', '/', $path));

    return array_pop($arr);
}

function max_upload_size()
{
    return min(asBytes(ini_get('post_max_size')), asBytes(ini_get('upload_max_filesize')));
}
