<?php

use Illuminate\Database\Seeder;

use App\Website;

use Faker\Factory;

class WebsitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Website::truncate();

        Website::create([
            'user_id' => 1,
            'name' => 'Most recent HN',
            'url' => 'https://news.ycombinator.com/news?p=',
        ]);

        for ($i = 1; $i < 10; $i++) {
            Website::create([
                'user_id' => 1,
                'name' => $faker->company,
                'url' => $faker->url,
            ]);
        }
    }
}
