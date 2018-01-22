<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Jobs\PopulateFilter;
use App\Filter;

class PopulateFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(\DatabaseSeeder::class);
    }

    public function testPopulateFilter()
    {
        $snapshot = Filter::first();

        $this->assertTrue($snapshot->scanned == 0);

        $job = new DownloadPage($snapshot);

        $job->handle();

        $snapshot->refresh();

        $page = $snapshot->pages()->first();

        $this->assertTrue($snapshot->pages()->exists());
        $this->assertTrue($snapshot->isCompleted());
    }

    public function testNotFound()
    {
        $snapshot = Snapshot::first();

        $snapshot->total = 6;

        $job = new DownloadPage($snapshot);

        $job->handle();

        $snapshot->refresh();

        $this->assertTrue($snapshot->pages()->where('status_code', '>', '200')->exists());
        $this->assertTrue($snapshot->isCompleted());
    }
}