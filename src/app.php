<?php



use DI\Container;
use Boot\Foundation\AppFactoryBridge as App;

$app = App::create(new Container);



$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$_SERVER['app'] = &$app;

if (!function_exists('app'))
{
    function app()
    {
        return $_SERVER['app'];
    }
}

return $app;