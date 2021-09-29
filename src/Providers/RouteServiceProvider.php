<?php


namespace Boot\Providers;



use Boot\Support\RouteGroup;
use Boot\Foundation\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->apiRouteGroup()->register();
        $this->webRouteGroup()->register();
        $get = routes_path('pages.php');
        if(file_exists($get)) $this->pagesRouteGroup($get)->register();

    }

    public function apiRouteGroup() : RouteGroup
    {
        $get = routes_path('api.php');
        $add = $this->resolve('middleware');
        $api = $this->resolve(RouteGroup::class);

        return $api->routes($get)->prefix('/api')->middleware([
            ...$add['api'],
            ...$add['global']
        ]);
    }

    public function webRouteGroup() : RouteGroup
    {
        $get = routes_path('web.php');
        $add = $this->resolve('middleware');
        $web = $this->resolve(RouteGroup::class);

        return $web->routes($get)->prefix('')->middleware([
            ...$add['web'],
            ...$add['global']
        ]);
    }

    public function pagesRouteGroup($get) : RouteGroup
    {
        $web = $this->resolve(RouteGroup::class);
        $add = $this->resolve('middleware');

        return $web->routes($get)->prefix('pages')->middleware([
            ...$add['web'],
            ...$add['global']
        ]);
    }
}