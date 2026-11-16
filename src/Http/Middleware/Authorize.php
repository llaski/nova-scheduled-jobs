<?php

namespace Llaski\NovaScheduledJobs\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Llaski\NovaScheduledJobs\Tool as NovaScheduledJobsTool;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tool = collect(Nova::registeredTools())->first([$this, 'matchesTool']);

        if (is_null($tool)) {
            abort(404);
        }

        if (! $tool->authorize($request)) {
            abort(403);
        }

        return $next($request);
    }

    /**
     * Determine whether this tool belongs to the package.
     */
    public function matchesTool(Tool $tool): bool
    {
        return $tool instanceof NovaScheduledJobsTool;
    }
}
