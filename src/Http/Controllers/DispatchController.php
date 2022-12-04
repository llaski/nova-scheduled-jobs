<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Console\Scheduling\Schedule;
use Llaski\NovaScheduledJobs\Schedule\EventFactory;
use Llaski\NovaScheduledJobs\Http\Resources\JobResource;
use Llaski\NovaScheduledJobs\Http\Resources\JobCollection;

class DispatchController
{
    /**
     * Dispatch Job
     *
     * @param  \Illuminate\Http\Request   $request
     * @return void
     *
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'command' => ['required', 'string', function ($attribute, $value, $fail) {
                if (class_exists($value) && strpos($value, '\Jobs') !== false) {
                    return;
                }

                $fail($value . ' is not a valid job.');
            },]
        ]);

        $command = resolve($data['command']);

        dispatch($command);
    }
}
