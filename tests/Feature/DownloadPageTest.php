<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\DownloadPage;
use App\User;
use App\Snapshot;

class DownloadPageTest extends TestCase
{
    use RefreshDatabase;

    public function testDownloadPage()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

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
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        // go to an non-existant number
        $snapshot->total = 3;

        $job = new DownloadPage($snapshot);

        $job->handle();

        $snapshot->refresh();

        $this->assertTrue($snapshot->pages()->where('status_code', '>', '200')->exists());
        $this->assertTrue($snapshot->isCompleted());
    }
}