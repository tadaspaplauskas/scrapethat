<?php

use Illuminate\Database\Seeder;

use App\Variable;
use App\Snapshot;

use Faker\Factory;

class VariableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Variable::truncate();

        foreach (Snapshot::all() as $snapshot) {
            $variable = new Variable([
                'snapshot_id' => $snapshot->id,
                'name' => 'price',
                'selector' => '.price',
            ]);

            $variable->values = collect(["58 000 \u20ac","78 000 \u20ac","60 800 \u20ac","60 500 \u20ac","86 000 \u20ac","83 500 \u20ac","59 650 \u20ac","67 700 \u20ac","73 800 \u20ac","65 000 \u20ac","67 800 \u20ac","80 000 \u20ac","54 650 \u20ac","86 000 \u20ac","94 000","91 140 \u20ac","57 000 \u20ac","40 000 \u20ac","45 000 \u20ac","79 900 \u20ac","67 400 \u20ac","77 037 \u20ac","114 185 \u20ac","53 200 \u20ac","77 000 \u20ac","58 000 \u20ac","78 000 \u20ac","60 800 \u20ac","60 500 \u20ac","86 000 \u20ac","83 500 \u20ac","59 650 \u20ac","67 700 \u20ac","73 800 \u20ac","65 000 \u20ac","67 800 \u20ac","80 000 \u20ac","54 650 \u20ac","86 000 \u20ac","94 000","91 140 \u20ac","57 000 \u20ac","40 000 \u20ac","45 000 \u20ac","79 900 \u20ac","67 400 \u20ac","77 037 \u20ac","114 185 \u20ac","53 200 \u20ac","77 000 \u20ac","58 000 \u20ac","78 000 \u20ac","60 800 \u20ac","60 500 \u20ac","86 000 \u20ac","83 500 \u20ac","59 650 \u20ac","67 700 \u20ac","73 800 \u20ac","65 000 \u20ac","67 800 \u20ac","80 000 \u20ac","54 650 \u20ac","86 000 \u20ac","94 000","91 140 \u20ac","57 000 \u20ac","40 000 \u20ac","45 000 \u20ac","79 900 \u20ac","67 400 \u20ac","77 037 \u20ac","114 185 \u20ac","53 200 \u20ac","77 000 \u20ac","58 000 \u20ac","78 000 \u20ac","60 800 \u20ac","60 500 \u20ac","86 000 \u20ac","83 500 \u20ac","59 650 \u20ac","67 700 \u20ac","73 800 \u20ac","65 000 \u20ac","67 800 \u20ac","80 000 \u20ac","54 650 \u20ac","86 000 \u20ac","94 000","91 140 \u20ac","57 000 \u20ac","40 000 \u20ac","45 000 \u20ac","79 900 \u20ac","67 400 \u20ac","77 037 \u20ac","114 185 \u20ac","53 200 \u20ac","77 000 \u20ac","58 000 \u20ac","78 000 \u20ac","60 800 \u20ac","60 500 \u20ac","86 000 \u20ac","83 500 \u20ac","59 650 \u20ac","67 700 \u20ac","73 800 \u20ac","65 000 \u20ac","67 800 \u20ac","80 000 \u20ac","54 650 \u20ac","86 000 \u20ac","94 000","91 140 \u20ac","57 000 \u20ac","40 000 \u20ac","45 000 \u20ac","79 900 \u20ac","67 400 \u20ac","77 037 \u20ac","114 185 \u20ac","53 200 \u20ac","77 000 \u20ac"]);

            $variable->save();

            $variable = new Variable([
                'snapshot_id' => $snapshot->id,
                'name' => 'square_price',
                'selector' => '.meters',
            ]);

            $variable->values = collect(["42.6","53.91","39.42","44.95","57","53.5","39.78","47","54","49.26","52.08","57","34.23","70.61","53.61","52.08","47.55","25.93","29.89","46.63","47.11","50.89","88.17","34.33","51.07","42.6","53.91","39.42","44.95","57","53.5","39.78","47","54","49.26","52.08","57","34.23","70.61","53.61","52.08","47.55","25.93","29.89","46.63","47.11","50.89","88.17","34.33","51.07","42.6","53.91","39.42","44.95","57","53.5","39.78","47","54","49.26","52.08","57","34.23","70.61","53.61","52.08","47.55","25.93","29.89","46.63","47.11","50.89","88.17","34.33","51.07","42.6","53.91","39.42","44.95","57","53.5","39.78","47","54","49.26","52.08","57","34.23","70.61","53.61","52.08","47.55","25.93","29.89","46.63","47.11","50.89","88.17","34.33","51.07","42.6","53.91","39.42","44.95","57","53.5","39.78","47","54","49.26","52.08","57","34.23","70.61","53.61","52.08","47.55","25.93","29.89","46.63","47.11","50.89","88.17","34.33","51.07"]);

            $variable->save();
        }
    }
}
