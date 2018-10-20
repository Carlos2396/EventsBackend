<?php

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;


class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tickets')->insert([
            'event_id' => 1,
            'user_id' => 2,
            'code' => Uuid::uuid1(),
            'created_at'=> Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('tickets')->insert([
            'event_id' => 1,
            'user_id' => 3,
            'code' => Uuid::uuid1(),
            'created_at'=> Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
