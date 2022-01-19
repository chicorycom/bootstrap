<?php


namespace Boot\Support;

use Illuminate\Support\Str;
use Slim\Routing\RouteContext;

class Route
{
    public static $app;

    public static function setup(&$app)
    {
        self::$app = $app;

        return $app;
    }

    public static function __callStatic($verb, $parameters)
    {
        $app = self::$app;

        [$route, $action] = $parameters;

        if(is_callable($action)){
            return $app->$verb($route, $action);
        }
        return ((is_string($action) and Str::is("*@*", $action)))
            ? $app->$verb($route, self::resolveViaController($action))
            : $app->$verb($route, $action) ;
    }

    /**
     * @param $action
     * @return array
     */
    public static function resolveViaController($action): array
    {

        $class = Str::before($action, '@');
        $method = Str::after($action, '@');

        $namespaces = config('routing.controllers.namespaces');
        if(!$namespaces){
            $namespace = config('routing.controllers.namespace');
            if(is_array($namespace)){
                return [$namespace[0] . $class, $method];
            }

            return [$namespace . $class, $method];
        }

        foreach ($namespaces as $namespace)
        {
            if (class_exists($namespace . $class))
            {
                $controller = $namespace . $class;
            }
        }

        throw_when(!isset($controller), "Unresolvable action, wasn't able to find controller for {$action}");

        return [$controller, $method];
    }

    protected static function validation($route, $verb, $action)
    {

        $exception = "Unresolvable Route Callback/Controller action";
        $context = json_encode(compact('route', 'action', 'verb'));
        //dd(is_callable($action, true) );
        $fails = !((is_callable($action, true)) or (is_string($action) and Str::is("*@*", $action)));
        //dd($fails);
        throw_when($fails, $exception . $context);
    }
}
