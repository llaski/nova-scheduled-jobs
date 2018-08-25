<?php

namespace Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /*
    Optional Description that would be displayed for package, not necessary to run job
     */
    public $description = 'Fake job to update orders...';

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Some fake logic goes here...
    }
}
