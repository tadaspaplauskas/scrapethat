<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Snapshot;

class SnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->visit('/snapshots')
            ->see('Snapshots');
    }

    public function testCreate()
    {
        $user = factory(User::class)->create();
        
        $this->actingAs($user)
            ->visitRoute('snapshots.create')
            ->type('Sample snapshot', 'name')
            ->type(config('app.url') . '/tests/*.html', 'url')
            ->type('1', 'from')
            ->type('2', 'to')
            ->press('Save')
            ->seeRouteIs('snapshots.index')
            ->see('Sample snapshot');
    }

    public function testShow()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $this->actingAs($user)
            ->visitRoute('snapshots.show', $snapshot->id)
            ->see($snapshot->name);
    }

    public function testDeleteAndRestore()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $this->actingAs($user)
            ->visitRoute('snapshots.index')
            ->press('Delete')
            ->see('was deleted')
            ->dontSee('Most recent HN submisisons')
            ->click('Undo')
            ->see('restored')
            ->see($snapshot->name);
    }
}
