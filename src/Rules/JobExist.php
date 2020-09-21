<?php

namespace Llaski\NovaScheduledJobs\Rules;

use Illuminate\Contracts\Validation\Rule;

class JobExist implements Rule
{
    public function passes($attribute, $value)
    {
        // If it's a job class
        if(class_exists($value)) {
            return strpos($value, '\Job') !== false;
        }

        // Check if it's a registered command
        return isset(\Artisan::all()[$value]);
    }

    public function message()
    {
        return 'The given value must be a valid command or job.';
    }
}
