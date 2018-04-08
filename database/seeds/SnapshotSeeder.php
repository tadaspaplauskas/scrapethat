<?php

use Illuminate\Database\Seeder;

use App\Snapshot;
use App\User;

use Faker\Factory;

class SnapshotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Snapshot::truncate();

        Snapshot::create([
            'user_id' => User::first()->id,
            'name' => 'Most recent HN submisisons',
            'url' => 'http://scrapethat.loc/tests/*.html',
            'from' => 1,
            'to' => 2,
            'downloaded' => 0,
            'total' => 2,
        ]);

        for ($i = 1; $i < 10; $i++) {
            Snapshot::create([
                'user_id' => User::first()->id,
                'name' => $faker->company,
                'url' => 'http://scrapethat.loc/tests/*.html',
                'from' => 1,
                'to' => 2,
                'downloaded' => 0,
                'total' => 2,
            ]);
        }
    }
}
