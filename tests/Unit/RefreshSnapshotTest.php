<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Snapshot;
use Carbon\Carbon;
use App\Console\Commands\RefreshSnapshots;

class RefreshSnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldRefreshSnapshot()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $snapshot->refresh_daily = 1;
        $snapshot->updated_at = Carbon::yesterday();
        $snapshot->save();

        // check if updated_at timestamp is of yesterday
        $snapshot->refresh();
        $this->assertTrue($snapshot->updated_at < Carbon::today());

        $command = new RefreshSnapshots;
        $command->handle();

        // timestamp should be updated now
        $snapshot->refresh();
        $this->assertTrue($snapshot->updated_at > Carbon::today());
    }

    public function testShouldNotRefreshSnapshot()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $snapshot->refresh_daily = 0;
        $snapshot->updated_at = Carbon::yesterday();
        $snapshot->save();

        // check if updated_at timestamp is of yesterday
        $snapshot->refresh();
        $this->assertTrue($snapshot->updated_at < Carbon::today());

        $command = new RefreshSnapshots;
        $command->handle();

        // timestamp should be updated now
        $snapshot->refresh();
        $this->assertTrue((string) $snapshot->updated_at === (string) Carbon::yesterday());
    }
}
