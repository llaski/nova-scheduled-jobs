<?php

namespace Llaski\NovaScheduledJobs\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JobCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $expressionSpacing = $this->getExpressionSpacing();

        return $this->collection->map(function ($resource) use ($request, $expressionSpacing) {
            return $resource->setExpressionSpacing($expressionSpacing)->toArray($request);
        });
    }

    /**
     * Gets the spacing to be used on each event row.
     *
     * @param  \Illuminate\Support\Collection  $events
     * @return array<int, int>
     */
    private function getExpressionSpacing()
    {
        $rows = $this->collection->map(fn ($event) => array_map('mb_strlen', preg_split("/\s+/", $event->expression)));

        return collect($rows[0] ?? [])->keys()->map(fn ($key) => $rows->max($key));
    }
}
