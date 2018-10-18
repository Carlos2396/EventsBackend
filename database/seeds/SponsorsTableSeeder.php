<?php

use Illuminate\Database\Seeder;
use App\Models\Sponsor;

class SponsorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sponsor::create([
            'name' => 'Microsoft',
            'image' => 'https://en.wikipedia.org/wiki/Microsoft#/media/File:Microsoft_logo_(2012).svg',
            'event_id' => 1
        ]);

        Sponsor::create([
            'name' => 'EA',
            'image' => 'https://en.wikipedia.org/wiki/Electronic_Arts#/media/File:Electronic-Arts-Logo.svg',
            'event_id' => 1
        ]);
    }
}
