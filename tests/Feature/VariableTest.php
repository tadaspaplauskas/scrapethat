<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Snapshot;
use App\Models\Variable;
use App\Models\Page;

class VariableTest extends TestCase
{
    use RefreshDatabase;

    public function testVariableIndex()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        
        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user)
            ->visitRoute('variables.index', $snapshot->id)
            ->see($variable->name);
    }

    public function testVariableCreate()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $page = $snapshot->pages()->save(factory(Page::class)->make());

        // mark as completed
        $snapshot->current = $snapshot->to;
        $snapshot->save();
        
        $this->actingAs($user)
            ->visitRoute('variables.create', $snapshot->id)
            ->type('score', 'name')
            ->type('.score', 'selector')
            ->press('Save')
            ->seeRouteIs('variables.index', $snapshot->id)
            ->see('.score');
    }

    public function testVariableDestroy()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user)
            ->visitRoute('variables.index', $snapshot->id)
            ->see($variable->name)
            ->press('Delete')
            ->see('was deleted');
    }
}
