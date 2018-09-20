<?php

namespace Llaski\NovaScheduledJobs\Rules;

use Illuminate\Contracts\Validation\Rule;

class JobExist implements Rule
{
    public function passes($attribute, $value)
    {
        return class_exists($value) && strpos($value, '\Jobs') !== false;
    }
    
    public function message()
    {
        return 'The given value must be a valid job.';
    }
}
