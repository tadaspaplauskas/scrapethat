<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use SnapshotSeeder;
use App\User;

class SnapshotTest extends DuskTestCase
{
    public function testIndex()
    {
        $this->seed(SnapshotSeeder::class);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                ->visit('/snapshots')
                ->assertSee('Snapshots');
        });
    }

    public function testCreate()
    {
        $this->seed(SnapshotSeeder::class);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                ->visit('/snapshots/create')
                ->type('name', 'Sample snapshot crawl')
                ->type('url', 'http://localhost')
                ->type('from', '10')
                ->type('to', '100')
                ->press('SAVE')
                ->assertSee('Sample snapshot crawl')
                ->assertPathIs('/snapshots');
        });
    }
}
