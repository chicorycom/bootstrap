<?php

namespace Boot\Foundation\Bootstrappers;

use Boot\Support\Console;
use Symfony\Component\Console\Application;

class LoadConsoleEnvironment extends Bootstrapper
{
    public function beforeBoot()
    {
        $console = new Application;
        $this->app->bind(Application::class, $console);

        Console::setup($this->app, $console);
    }
}
