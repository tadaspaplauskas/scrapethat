<?php

use Illuminate\Database\Seeder;

use App\Website;

class WebsitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Website::truncate();

        Website::create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'Most recent HN',
            'url' => 'https://news.ycombinator.com/news?p=',
        ]);        
    }
}
