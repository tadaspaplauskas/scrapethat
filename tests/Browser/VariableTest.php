<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;
use App\Snapshot;
use App\Variable;
use App\Page;

class VariableTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testVariableIndex()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        
        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->browse(function (Browser $browser) use ($user, $snapshot, $variable) {
            $browser->loginAs($user)
                ->visit('/snapshots/' . $snapshot->id . '/variables')
                ->assertSee($variable->name);
        });
    }

    public function testVariableCreate()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        // mark as completed
        $snapshot->current = $snapshot->to;
        $snapshot->save();
        
        $this->browse(function (Browser $browser) use ($user, $snapshot) {
            $browser->loginAs($user)
                ->visit('/snapshots/' . $snapshot->id . '/variables/create')
                ->type('name', 'score')
                ->type('selector', '.score')
                ->press('SAVE')
                ->assertPathIs('/snapshots/' . $snapshot->id . '/variables')
                ->assertSee('.score');
        });
    }

    public function testVariableDestroy()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->browse(function (Browser $browser) use ($user, $snapshot, $variable) {
            $browser->loginAs($user)
                ->visit('/snapshots/' . $snapshot->id . '/variables')
                ->assertSee($variable->name)
                ->press('DELETE')
                ->assertSee('was deleted')
                ->assertDontSee($variable->name);
        });
    }
}
