<?php

use Illuminate\Database\Seeder;
use App\Models\Extra;


class ExtrasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Extra::create([
            'event_id' => 1,
            'text' => 'What is your T-Shirt Size',
        ]);

        Extra::create([
            'event_id' => 1,
            'text' => 'What is your age?',
        ]);
    }
}
