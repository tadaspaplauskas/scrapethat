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

        foreach (Snapshot::all() as $snapshot) {
            $filter = new Filter([
                'snapshot_id' => $snapshot->id,
                'name' => 'dataX',
                'selector' => '.data',
            ]);

            $filter->values = [1, 2, 4, 5, 6];

            $filter->save();
        }
    }
}
