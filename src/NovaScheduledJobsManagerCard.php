<?php

namespace Llaski\NovaScheduledJobs;

use Laravel\Nova\Card;
use Laravel\Nova\Nova;
use Llaski\NovaScheduledJobs\Http\Middleware\Authorize;

class NovaScheduledJobsManagerCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'nova-scheduled-jobs-manage';
    }

    public function __construct($component = null)
    {
        parent::__construct($component);

        // Set default authorisation
        $this->canSee(function ($request) {
            return NovaScheduledJobsTool::defaultAuthorize($request);
        });
    }
}
