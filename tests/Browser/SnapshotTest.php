<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;
use App\Snapshot;

class SnapshotTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/snapshots')
                ->assertSee('Snapshots');
        });
    }

    public function testCreate()
    {
        $user = factory(User::class)->create();
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/snapshots/create')
                ->type('name', 'Sample snapshot')
                ->type('url', 'http://crawler.loc/tests/*.html')
                ->type('from', '1')
                ->type('to', '5')
                ->press('SAVE')
                ->assertPathIs('/snapshots')
                ->assertSeeLink('Sample snapshot');
        });
    }

    public function testShow()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $this->browse(function (Browser $browser) use ($user, $snapshot) {
            $browser->loginAs($user)
                ->visit('/snapshots/' . $snapshot->id)
                ->assertSee($snapshot->name);
        });
    }

    public function testDeleteAndRestore()
    {
        $user = factory(User::class)->create();

        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());

        $this->browse(function (Browser $browser) use ($user, $snapshot) {
            $browser->loginAs($user)
                ->visit('/snapshots/' . $snapshot->id)
                ->clickLink('Delete snapshot')
                ->press('DELETE')
                ->assertSee('was deleted')
                ->assertDontSeeLink('Most recent HN submisisons')
                ->clickLink('Undo')
                ->waitForText('restored')
                ->assertSeeLink($snapshot->name);
        });
    }
}
