<?php

namespace MinRep;

use Baguette\Response\TwigResponse;

final class Application extends \Baguette\Application
{
    const VERSION = '0.0.1';

    use \Baguette\Application\Param;

    /** @var \Baguette\Session\SessionInterface $session */
    private $session;

    /**
     * @param  \Teto\Routing\Action $action
     * @return \Baguette\Response\ResponseInterface
     */
    public function execute(\Teto\Routing\Action $action)
    {
        // Whoops
        $this->whoops = new \Whoops\Run;
        $this->whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $this->whoops->register();

        if ($action->value instanceof \Closure) {
            return call_user_func($action->value, $action);
        }

        var_dump($action);exit;
    }

    /**
     * @param  string $name
     * @param  array  $params
     * @param  int    $status_code
     * @return \Baguette\Response\ResponseInterface
     */
    public function template($name, array $params, $status_code = 200)
    {
        return new TwigResponse("{$name}.tpl.html", $params + [
            'server'  => $this->server,
            'cookie'  => $this->cookie,
            'get'     => $this->get,
            'post'    => $this->post,
            'now'     => $this->now,
        ], $status_code);
    }

    public function setSession(\Baguette\Session\SessionInterface $session)
    {
        $this->session = $session;
        $session->start();
    }

    public function setTwig(\Twig_Environment $twig, \Teto\Routing\Router $router)
    {
        $url_to = function ($name, array $param = []) use ($router) {
            return $router->makePath($name, $param, true);
        };
        $twig->addFunction(new \Twig_SimpleFunction('url_to', $url_to));

        TwigResponse::setTwigEnvironment($twig);
    }
}
