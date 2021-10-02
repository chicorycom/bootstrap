<?php

namespace Boot\Console\Events;

class ArtisanStarting
{
    /**
     * The Artisan application instance.
     *
     * @var \Boot\Console\Application
     */
    public $artisan;

    /**
     * Create a new event instance.
     *
     * @param  \Boot\Console\Application  $artisan
     * @return void
     */
    public function __construct($artisan)
    {
        $this->artisan = $artisan;
    }
}
