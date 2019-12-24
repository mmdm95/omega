<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

register_shutdown_function('shut');
set_error_handler('handler');
set_exception_handler("log_exception");

//Function to catch no user error handler function errors...
function shut()
{
    $error = error_get_last();
    if ($error && ($error['type'] & E_FATAL)) {
        handler($error['type'], $error['message'], $error['file'], $error['line']);
    }
}

function handler($errno, $errstr, $errfile, $errline)
{
    if (error_reporting() == 0) {
        return;
    }

    $H =& HController::get_instance();

    $typestr = 'UNDEFINED';
    switch ($errno) {
        case E_ERROR: // 1 //
            $typestr = 'E_ERROR';
            break;
        case E_WARNING: // 2 //
            $typestr = 'E_WARNING';
            break;
        case E_PARSE: // 4 //
            $typestr = 'E_PARSE';
            break;
        case E_NOTICE: // 8 //
            $typestr = 'E_NOTICE';
            break;
        case E_CORE_ERROR: // 16 //
            $typestr = 'E_CORE_ERROR';
            break;
        case E_CORE_WARNING: // 32 //
            $typestr = 'E_CORE_WARNING';
            break;
        case E_COMPILE_ERROR: // 64 //
            $typestr = 'E_COMPILE_ERROR';
            break;
        case E_COMPILE_WARNING: // 128 //
            $typestr = 'E_COMPILE_WARNING';
            break;
        case E_USER_ERROR: // 256 //
            $typestr = 'E_USER_ERROR';
            break;
        case E_USER_WARNING: // 512 //
            $typestr = 'E_USER_WARNING';
            break;
        case E_USER_NOTICE: // 1024 //
            $typestr = 'E_USER_NOTICE';
            break;
        case E_STRICT: // 2048 //
            $typestr = 'E_STRICT';
            break;
        case E_RECOVERABLE_ERROR: // 4096 //
            $typestr = 'E_RECOVERABLE_ERROR';
            break;
        case E_DEPRECATED: // 8192 //
            $typestr = 'E_DEPRECATED';
            break;
        case E_USER_DEPRECATED: // 16384 //
            $typestr = 'E_USER_DEPRECATED';
            break;
    }

    if (($errno & E_NOTICE) && (strtolower(ENVIRONMENT) == 'semi_development' || strtolower(ENVIRONMENT) == 'semi_dev')) return;

    $message = '<b>' . $typestr . ': </b>' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b><br/>';

    // Set error in data to pass it to page(s)
    $data['Exceptions_message'] = $message;
    $data['Exceptions_detail'] = [
        'type' => $errno,
        'typeStr' => $typestr,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ];
//    $Exceptions_message = $message;
//    $Exceptions_detail = [
//        'type' => $errno,
//        'typeStr' => $typestr,
//        'message' => $errstr,
//        'file' => $errfile,
//        'line' => $errline
//    ];

    if (($errno & E_FATAL) && (strtolower(ENVIRONMENT) === 'release' || strtolower(ENVIRONMENT) === 'rel')) {
//        header('Location: ' . BASE_URL . 'Errors/serverError');
        header('Status: 500 Internal Server Error');
        $H->load->view('errors/Err_ServerErr');
        exit();
    }

    if (!($errno & ERROR_REPORTING))
//        $H->load->view('errors/Err_AnyErr');
        return;
    if (DISPLAY_ERRORS) {
        try {
            if ($errno == E_ERROR) {
                printf('%s', $message);
            } else {
//                include VIEW_PATH . 'errors/Err_AnyErr.php';
                $H->load->view('errors/Err_AnyErr', $data);
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    }
    //Logging error on php file error log...
    if (LOG_ERRORS)
        error_log(strip_tags($message), 0);
//    die();
}

/**
 * Uncaught exception handler.
 * @see https://www.php.net/manual/en/function.set-error-handler.php#112291
 */
function log_exception($e)
{
    $H =& HController::get_instance();

    if (DISPLAY_ERRORS) {
        print "<div style='text-align: center;'>";
        print "<h2 style='color: rgb(190, 50, 50); direction: rtl;'>خطا رخ داده است:</h2>";
        print "<table style='max-width: 800px; display: inline-block;'><tbody>";
        print "<tr style='background-color:rgb(230,230,230);'><th style='width: 80px;'>Type</th><td>" . get_class($e) . "</td></tr>";
        print "<tr style='background-color:rgb(240,240,240);'><th>Message</th><td>{$e->getMessage()}</td></tr>";
        print "<tr style='background-color:rgb(230,230,230);'><th>File</th><td>{$e->getFile()}</td></tr>";
        print "<tr style='background-color:rgb(240,240,240);'><th>Line</th><td>{$e->getLine()}</td></tr>";
        print "<tr style='background-color:rgb(230,230,230);'><th>Trace</th><td>{$e->getTraceAsString()}</td></tr>";
        print "</table></tbody></div>";
    } else {
        $message = "Type: " . get_class($e) . "; Message: {$e->getMessage()}; File: {$e->getFile()}; Line: {$e->getLine()};";
        file_put_contents(APP_PATH . "log/exceptions.log", $message . PHP_EOL, FILE_APPEND);
        header('Status: 500 Internal Server Error');
        $H->load->view('errors/Err_ServerErr');
    }

    exit();
}

//ob_start();
//@include 'content.php';
//ob_end_flush();
