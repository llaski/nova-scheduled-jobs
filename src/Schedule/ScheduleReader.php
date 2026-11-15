<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Collection;

class ScheduleReader
{
    /**
     * Return the application's scheduled events, ensuring the console kernel
     * has been bootstrapped so schedules defined in the kernel (or
     * routes/console.php) are registered for HTTP requests as well.
     *
     * @see https://github.com/laravel/framework/issues/51369
     */
    public static function events(Kernel $kernel): Collection
    {
        $kernel->bootstrap();

        return collect(app(Schedule::class)->events())
            ->map(fn ($event) => EventFactory::make($event));
    }
}
