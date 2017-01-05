<?php

/**
 * Functions for MinRep application
 *
 * @copyright 2017 Baguette HQ
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @license   https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 */

/** @return bool */
function is_production()
{
     return getenv('MY_PHP_ENV') === 'production';
}

/**
 * バックトレースとログ情報を記録する関数
 */
function my_whoops_logger_handler($exception, $inspector, $run)
{
    $logfile = __DIR__ . '/../error.log';
    if (is_writable($logfile)) {
        $data = \Whoops\Exception\Formatter::formatExceptionAsDataArray(
            $inspector, true
        ) + [
            'time'     => (string)$_SERVER['REQUEST_TIME_FLOAT'],
            '$_SERVER' => $_SERVER,
            '$_GET'    => $_GET,
            '$_POST'   => $_POST,
            '$_COOKIE' => $_COOKIE,
        ];
        $json = json_encode($data, JSON_UNESCAPED_SLASHES);
        file_put_contents($logfile, $json . "\n", FILE_APPEND);
    }

    return null;
}
