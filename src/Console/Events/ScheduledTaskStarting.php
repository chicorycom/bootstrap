<?php

namespace Boot\Console\Events;

use Boot\Console\Scheduling\Event;

class ScheduledTaskStarting
{
    /**
     * The scheduled event being run.
     *
     * @var \Boot\Console\Scheduling\Event
     */
    public $task;

    /**
     * Create a new event instance.
     *
     * @param  \Boot\Console\Scheduling\Event  $task
     * @return void
     */
    public function __construct(Event $task)
    {
        $this->task = $task;
    }
}
