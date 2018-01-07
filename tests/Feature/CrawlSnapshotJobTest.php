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

        $this->assertTrue($snapshot->crawled === 0);

        $job = new CrawlSnapshot($snapshot);

        $job->handle();

        $page = $snapshot->pages()->first();

        $this->assertTrue($snapshot->pages()->exists());
        $this->assertTrue($snapshot->crawled === 1);
    }
}