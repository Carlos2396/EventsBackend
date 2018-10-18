<?php

use Illuminate\Database\Seeder;
use App\Models\Answer;
use Carbon\Carbon;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert ([
            'user_id' => 2,
            'extra_id' => 1,
            'answer' => 'Mediana',
            'created_at'=> Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert ([
            'user_id' => 2,
            'extra_id' => 1,
            'answer' => '60',
            'created_at'=> Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
