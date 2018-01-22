<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Jobs\DownloadPage;
use App\Snapshot;

class DownloadPageTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->seed(\DatabaseSeeder::class);
    }

    public function testDownloadPage()
    {
        $snapshot = Snapshot::first();

        $this->assertTrue($snapshot->crawled == 0);

        $job = new DownloadPage($snapshot);

        $job->handle();

        $snapshot->refresh();

        $page = $snapshot->pages()->first();

        $this->assertTrue($snapshot->pages()->exists());
        $this->assertTrue($snapshot->isCompleted());
    }

    public function testDownloadPageNotFound()
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