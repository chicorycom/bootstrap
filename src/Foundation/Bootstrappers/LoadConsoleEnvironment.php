<?php


namespace Boot\Foundation\Bootstrappers;


use Boot\Foundation\Console\Application;
use Boot\Foundation\Console\Console;

class LoadConsoleEnvironment extends Bootstrapper
{
    public function beforeBoot()
    {
        $console = new Application($this->app->version());
        $this->app->bind(Application::class, $console);
       Console::setup($this->app, $console);
    }
}
