<?php

/**
 * Entry point of MinRep application
 *
 * @copyright 2017 Baguette HQ
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @license   https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 */

namespace MinRep;

use Teto\Routing\Action;

error_reporting(E_ALL);
$basedir = dirname(__DIR__);
$loader = require "{$basedir}/app/bootstrap.php";

$app = new \MinRep\Application($_SERVER, $_COOKIE, $_GET, $_POST);

$routes = [];

$routes['user'] = ['GET', '/:user', function (Action $action) use ($app) {
    return $app->template('user', []);
}, ['user' => '/\A@([a-z][-a-z]+[a-z])\z/'], '?ext' => ['', 'json']];

$routes['index'] = ['GET', '/', function (Action $action) use ($app) {
    return $app->template('index', []);
}];

$routes['#404'] = function (Action $action) use ($app) {
    return $app->template('not_found', [], 404);
};

// Router
$router = new \Teto\Routing\Router($routes);

// Session
ini_set('session.save_path', "{$basedir}/cache/session");

$session = new \Baguette\Session\PhpSession;
$app->setSession($session);

// Twig
$twig = new \Twig_Environment(new \Twig_Loader_Filesystem("{$basedir}/app/view/twig"), [
    'cache' => "{$basedir}/cache/twig",
    'debug' => true,
]);
$app->setTwig($twig, $router);

// Exec App
$r = $app->execute($router->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']));
echo $app->renderResponse($r);
