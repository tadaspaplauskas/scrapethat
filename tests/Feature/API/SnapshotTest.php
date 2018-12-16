<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Snapshot;

class SnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function testSnapshotIndex()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/snapshots')
            ->assertStatus(200)
            ->assertJsonFragment(['name' => $snapshot->name]);
    }

    public function testSnapshotStore()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/v1/snapshots', [
                'name' => 'Sample snapshot',
                'url' => config('app.url') . '/tests/*.html',
                'from' => 1,
                'to' => 2,
            ])
            ->assertStatus(201)
            ->assertJson(['name' => 'Sample snapshot']);
    }

    public function testSnapshotUpdate()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $this->actingAs($user, 'api')
            ->json('PUT', '/api/v1/snapshots/' . $snapshot->id, [
                'name' => 'Updated snapshot',
                'url' => config('app.url') . '/tests/*.html',
                'from' => 1,
                'to' => 2,
            ])
            ->assertJson(['name' => 'Updated snapshot'])
            ->assertStatus(200);
    }

    public function testSnapshotShow()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/snapshots/' . $snapshot->id)
            ->assertStatus(200)
            ->assertJson(['name' => $snapshot->name]);
    }

    public function testSnapshotDestroy()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/v1/snapshots/' . $snapshot->id)
            ->assertStatus(204);
    }
}
