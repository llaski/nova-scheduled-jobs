<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use Llaski\NovaScheduledJobs\Schedule\ScheduleReader;

class DispatchController
{
    /**
     * Dispatch a scheduled job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Console\Kernel  $kernel
     * @return void
     *
     * @throws \Exception
     */
    public function __invoke(Request $request, Kernel $kernel)
    {
        $allowedJobs = ScheduleReader::events($kernel)
            ->map(fn ($event) => $event->command())
            ->filter(fn ($command) => is_string($command) && class_exists($command))
            ->unique()
            ->values()
            ->all();

        $data = $request->validate([
            'command' => ['required', 'string', function ($attribute, $value, $fail) use ($allowedJobs) {
                if (in_array($value, $allowedJobs, true)) {
                    return;
                }

                $fail($value . ' is not a valid scheduled job.');
            }],
        ]);

        dispatch(resolve($data['command']));
    }
}
