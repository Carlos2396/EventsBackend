<?php

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create([
            'name' => 'Hackathon 2022',
            'starts' => Carbon::now()->addWeeks(1),
            'ends' => Carbon::now()->addWeeks(2),
            'registration_start' => Carbon::now(),
            'registration_end' => Carbon::now()->addWeeks(1),
            'image' => null,
            'description' => 'Gecko-organized hackathon generation 2022 !!',
            'organizer_id' => 1,
            'guest_capacity' => 32,
            'event_type' => 'type1'
        ]);
    }
}
