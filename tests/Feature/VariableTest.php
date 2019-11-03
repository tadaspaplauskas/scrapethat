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

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make([
            'current' => 5,
            'to' => 5,
        ]));

        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user)
            ->visitRoute('snapshots.show', $snapshot)
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
            ->visitRoute('snapshots.show', $snapshot)
            ->click('Make a new one')
            ->type('Price', 'name')
            ->type('.price', 'selector')
            ->select('numeric', 'type')
            ->press('Save')
            ->see('added')
            ->see('.price');
    }

    public function testVariableEdit()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make([
            'current_page' => 99, // mark is as completed
        ]));

        $snapshot->download();

        // mark as completed
        $snapshot->current = $snapshot->to;
        $snapshot->save();

        $this->actingAs($user)
            ->visitRoute('snapshots.show', $snapshot)
            ->click('Edit')
            ->type('name updated', 'name')
            ->type('.price', 'selector')
            ->select('numeric', 'type')
            ->press('Save')
            ->see('name updated')
            ->see('.price');
    }

    public function testVariableDestroy()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make([
            'current' => 5,
            'to' => 5,
        ]));

        $variable = $snapshot->variables()->save(factory(Variable::class)->make([
            'current_page' => 99, // mark is as completed
        ]));

        $this->actingAs($user)
            ->visitRoute('snapshots.show', $snapshot)
            ->see($variable->name)
            ->click('Delete')
            ->press('Delete')
            ->see('was deleted');
    }
}
