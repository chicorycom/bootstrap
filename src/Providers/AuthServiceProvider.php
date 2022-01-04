<?php

namespace Boot\Providers;

use Boot\Support\Auth;

class AuthServiceProvider extends ServiceProvider
{
    protected function beforeRegistering()
    {
       //  app()->bind(Auth::class, fn () => (Auth::class));
    }

    public function register()
    {

    }
    public function boot()
    {

    }
}
