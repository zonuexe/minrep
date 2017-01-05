<?php

/**
 * Configures for bootstrapping MinRep application
 *
 * @copyright 2017 Baguette HQ
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @license   https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 */

namespace MinRep;

return call_user_func(function () {
    $loader = require __DIR__ . '/../vendor/autoload.php';

    mb_internal_encoding('UTF-8');

    if (file_exists(__DIR__ . '/../.env')) {
        $dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
        $dotenv->overload();
    }

    if ($timezone = getenv('MY_PHP_TIMEZONE')) {
        ini_set('date.timezone', $timezone);
    }

    return $loader;
});
