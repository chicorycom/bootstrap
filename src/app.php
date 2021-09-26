<?php



use DI\Container;
use Boot\Foundation\AppFactoryBridge as App;

$app = App::create(new Container);

$_SERVER['app'] = &$app;

if (!function_exists('app'))
{
    function app()
    {
        return $_SERVER['app'];
    }
}

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

return $app;