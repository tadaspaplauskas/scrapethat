<?php

use Illuminate\Database\Seeder;

use App\Snapshot;
use App\User;
use App\Page;

use Faker\Factory;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::truncate();
    }
}
