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
                ->visit('/snapshots')
                ->assertSee('Snapshots');
        });
    }
}
