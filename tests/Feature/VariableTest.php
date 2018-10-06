<?php

namespace Tests\Feature;

use Tests\BrowserTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Snapshot;
use App\Models\Variable;
use App\Models\Page;

class VariableTest extends BrowserTestCase
{
    use RefreshDatabase;

    public function testVariableIndex()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user)
            ->visitRoute('snapshots.show', $snapshot->id)
            ->see($variable->name);
    }

    public function testVariableCreate()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $snapshot->download();

        // mark as completed
        $snapshot->current = $snapshot->to;
        $snapshot->save();

        $this->actingAs($user)
            ->visitRoute('snapshots.show', $snapshot->id)
            ->type('Price', 'name')
            ->type('.price', 'selector')
            ->press('Add')
            ->see('added')
            ->see('.price');
    }

    public function testVariableDestroy()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user)
            ->visitRoute('snapshots.show', $snapshot->id)
            ->see($variable->name)
            ->press('Delete')
            ->see('was deleted');
    }
}
