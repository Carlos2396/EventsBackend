<?php

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create([
            'event_id' => 1,
            'name' => 'ITESM Puebla',
            'address' => 'Avenida Atlixcayotl #4',
            'lat' => 19.02055,
            'lng' => -98.24472
        ]);

        Location::create([
            'event_id' => 1,
            'name' => 'CCU',
            'address' => 'Avenida Atlixcayotl #5',
            'lat' => 19.02060,
            'lng' => -98.24472
        ]);
    }
}
