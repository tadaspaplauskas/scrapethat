<?php

use Illuminate\Database\Seeder;

use App\Filter;
use App\Snapshot;

use Faker\Factory;

class FilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Filter::truncate();

        Filter::create([
            'snapshot_id' => Snapshot::first()->id,
            'name' => 'dataX',
            'selector' => '.data',
        ]);
    }
}
