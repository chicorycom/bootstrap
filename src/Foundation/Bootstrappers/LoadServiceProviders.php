<?php


namespace Boot\Foundation\Bootstrappers;



use Boot\Providers\ServiceProvider;

class LoadServiceProviders extends Bootstrapper
{
    public function boot()
    {

        $app = $this->app;
        $providers = config('app.providers');

        if ($app->bootedViaHttpRequest()) {
            $providers = [...$providers, \Boot\Providers\RouteServiceProvider::class];
        } else if ($app->bootedViaConsole()) {
            $providers = [...$providers, \Boot\Providers\ConsoleServiceProvider::class];
        }

        ServiceProvider::setup($app, $providers);
       // ServiceProvider::setup($this->app, config('app.providers'));
    }
}