<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Illuminate\Http\Request;

class DispatchController
{
    /**
     * Dispatch a job by class name.
     *
     * @param  \Illuminate\Http\Request  $request
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
            }],
        ]);

        dispatch(resolve($data['command']));
    }
}
