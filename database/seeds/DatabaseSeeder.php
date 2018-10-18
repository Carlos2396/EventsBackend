<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ArticlesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(TicketsTableSeeder::class);
        $this->call(ExtrasTableSeeder::class);
        $this->call(AnswersTableSeeder::class);
        $this->call(SponsorsTableSeeder::class);

    }
}
