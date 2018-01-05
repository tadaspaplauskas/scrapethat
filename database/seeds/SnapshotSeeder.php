<?php

use Illuminate\Database\Seeder;

use App\Snapshot;

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
            'user_id' => 1,
            'name' => 'Most recent HN submisisons',
            'url' => 'http://localhost:8000/tests/1.html', // 'https://news.ycombinator.com/news?p=',
        ]);

        for ($i = 1; $i < 10; $i++) {
            Snapshot::create([
                'user_id' => 1,
                'name' => $faker->company,
                'url' => $faker->url,
            ]);
        }
    }
}
