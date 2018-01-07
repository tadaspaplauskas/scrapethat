<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;
use App\Snapshot;

class SnapshotTest extends DuskTestCase
{
    public function testIndex()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())
                ->visit('/snapshots')
                ->assertSee('Snapshots');
        });
    }

    public function testCreate()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())
                ->visit('/snapshots/create')
                ->type('name', 'Sample snapshot')
                ->type('url', 'http://localhost')
                ->type('from', '10')
                ->type('to', '100')
                ->press('SAVE')
                ->assertPathIs('/snapshots')
                ->assertSeeLink('Sample snapshot');
        });
    }

    public function testShow()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())
                ->visit('/snapshots/' . Snapshot::first()->id)
                ->assertSee('Most recent HN submisisons');
        });
    }

    public function testDeleteAndRestore()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())
                ->visit('/snapshots/' . Snapshot::first()->id)
                ->press('DELETE')
                ->assertSee('was deleted')
                ->assertDontSeeLink('Most recent HN submisisons')
                ->clickLink('Undo')
                ->waitForText('restored')
                ->assertSeeLink('Most recent HN submisisons');
        });
    }
}
