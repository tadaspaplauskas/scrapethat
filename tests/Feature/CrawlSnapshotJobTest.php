<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Jobs\CrawlSnapshot;
use App\Snapshot;

class CrawlSnapshotJobTest extends TestCase
{
    // use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(\DatabaseSeeder::class);
    }

    public function testHandle()
    {
        $snapshot = Snapshot::first();

        $job = new CrawlSnapshot($snapshot);

        $job->handle();
    }
}