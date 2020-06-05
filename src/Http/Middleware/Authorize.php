<?php

namespace Llaski\NovaScheduledJobs\Http\Middleware;

use Laravel\Nova\Nova;
use Llaski\NovaScheduledJobs\NovaScheduledJobsTool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return NovaScheduledJobsTool::authorize($request) ? $next($request) : abort(403);
    }

    /**
     * Determine whether this tool belongs to the package.
     *
     * @param  \Laravel\Nova\Tool  $tool
     * @return bool
     */
    public function matchesTool($tool)
    {
        return $tool instanceof NovaScheduledJobsTool;
    }
}
