<?php

use Illuminate\Database\Seeder;

use App\Models\User;

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
            'email' => 'tadas@paplauskas.lt',
            'name' => 'Tadas',
            'password' => bcrypt('secret'),
        ]);        
    }
}
