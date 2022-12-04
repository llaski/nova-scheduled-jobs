<?php

namespace Llaski\NovaScheduledJobs\Tests\Fixtures\Console\Tasks;

use Illuminate\Support\Facades\DB;

class DeleteRecentUsers
{
    public $description = 'delete-recent-users';

    public function __invoke($x)
    {
        DB::table('recent_users')->delete();
    }
}
