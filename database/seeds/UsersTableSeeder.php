<?php

use Illuminate\Database\Seeder;
use App\User;
use Ramsey\Uuid\Uuid;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // Let's make sure everyone has the same password and 
        // let's hash it before the loop, or else our seeder 
        // will be too slow.
        $password = Hash::make('secret');

        $admin = User::create([
            'firstname' => 'Administrator',
            'lastname' => 'Administrator',
            'confirmation_code' => Uuid::uuid1(),
            'gender' => 'other',
            'email' => 'admin@test.com',
            'password' => $password,
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'firstname' => 'User',
            'lastname' => 'User',
            'confirmation_code' => Uuid::uuid1(),
            'gender' => 'other',
            'email' => 'user@test.com',
            'password' => $password,
        ]);
        $user->assignRole('user');
    }
}
