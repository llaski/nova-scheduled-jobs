<?php

namespace Llaski\NovaScheduledJobs\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Llaski\NovaScheduledJobs\NovaScheduledJobsTool;

class Authorize
{
    public function handle(Request $request, Closure $next)
    {
        return app(NovaScheduledJobsTool::class)->authorize($request)
            ? $next($request)
            : abort(403);
    }
}