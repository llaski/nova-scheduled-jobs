<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Llaski\NovaScheduledJobs\Vendor\CronSchedule;

abstract class Event
{
    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    abstract public function command();

    abstract public function className();

    public function description()
    {
        try {
            $reflection = new \ReflectionClass($this->className());
            return (string) array_get($reflection->getDefaultProperties(), 'description', '');
        } catch (\ReflectionException $exception) {
            return '';
        }
    }

    public function humanReadableExpression()
    {
        return CronSchedule::fromCronString($this->event->expression)->asNaturalLanguage();
    }

    public function nextRunAt()
    {
        return Cron::nextRunAt($this->event->expression, $this->timezone());
    }

    public function timezone()
    {
        return $this->event->timezone ?: 'UTC';
    }

    public function __get($name)
    {
        return $this->event->{$name};
    }

}
