<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Llaski\NovaScheduledJobs\Rules\JobExist;

class DispatchJobController
{
    /**
     * Dispatch job command.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'command' => ['required', 'string', new JobExist]
        ]);

        // Dispatch a job
        if(class_exists($data['command'])) {
            $command = resolve($data['command']);

            dispatch($command);
            return;
        }

        // Otherwise call the command
        Artisan::call($data['command']);
    }
}
