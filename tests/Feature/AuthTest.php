<?php

namespace Tests\Browser;

use Tests\TestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function testRegistration()
    {
        $this->visit('/register')
            ->type('Tadas', 'name')
            ->type('tadas+test@paplauskas.lt', 'email')
            ->type('password', 'password')
            ->type('password', 'password_confirmation')
            ->press('Register')
            ->see('Log out');

        $this->seeInDatabase('users', ['email' => 'tadas+test@paplauskas.lt']);
    }

    public function testLogin()
    {
        $user = factory(\App\Models\User::class)->create([
            'email' => 'tadas+test@paplauskas.lt',
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type('secret', 'password')
            ->check('remember')
            ->press('Log in')
            ->see('Log out');
    }
}
