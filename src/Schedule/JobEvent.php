<?php

namespace Llaski\NovaScheduledJobs\Schedule;

class JobEvent extends Event
{

    public function command()
    {
        return $this->event->description ?? 'Closure';
    }

    public function className()
    {
        return $this->event->description;
    }

}
