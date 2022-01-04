<?php

namespace Boot\Foundation\Providers;

use Boot\Support\Route;
use Boot\Support\RouteGroup;

class RouteServiceProvider extends SlimServiceProvider
{
    public function beforeRegistering()
    {
        Route::setup($this->app);

        $this->bind(RouteGroup::class, fn () => new RouteGroup($this->app));
    }
}
