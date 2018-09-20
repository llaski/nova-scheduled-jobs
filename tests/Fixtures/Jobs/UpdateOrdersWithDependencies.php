<?php

namespace Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Processors\FakeOrderProcessor;

class UpdateOrdersWithDependencies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /*
    Optional Description that would be displayed for package, not necessary to run job
     */
    public $description = 'Fake job to update orders...';

    private $processor;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FakeOrderProcessor $processor)
    {
        $this->processor = $processor;
    }

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
