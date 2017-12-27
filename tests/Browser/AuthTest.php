<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'Tadas')
                ->type('email', 'tadas@paplauskas.lt')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('REGISTER')
                ->assertSee('Log out');
        });

        $this->assertDatabaseHas('users', ['email' => 'tadas@paplauskas.lt']);
    }

    public function testLogin()
    {
        $user = factory(\App\User::class)->create([
            'email' => 'tadas@paplauskas.lt',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'secret')
                ->check('remember')
                ->press('LOG IN')
                ->assertSee('Log out');
        });
    }
}
