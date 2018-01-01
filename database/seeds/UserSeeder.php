<?php

use Illuminate\Database\Seeder;

use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'id' => 1,
            'email' => 'tadas@paplauskas.lt',
            'name' => 'Tadas',
            'password' => bcrypt('secret'),
        ]);        
    }
}