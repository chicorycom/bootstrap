<?php

namespace Boot\Foundation\Providers;

use Boot\Foundation\Events\Dispatcher;


class EventServiceProvider extends SlimServiceProvider
{
    protected $events;

    protected function beforeRegistering()
    {
        $this->events = new \Boot\Foundation\Events\Dispatcher($this->app);

        $this->bind('events', fn () => $this->events);
    }
}
