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
            'birthdate' => '1996-05-04',
            'phone' => '2221456487',
            'email' => 'admin@test.com',
            'password' => $password,
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'firstname' => 'User',
            'lastname' => 'User',
            'confirmation_code' => Uuid::uuid1(),
            'gender' => 'other',
            'birthdate' => '1996-05-08',
            'phone' => '2221456488',
            'email' => 'user@test.com',
            'password' => $password,
        ]);
        $user->assignRole('user');

        $user2 = User::create([
            'firstname' => 'User2',
            'lastname' => 'User2',
            'confirmation_code' => Uuid::uuid1(),
            'gender' => 'other',
            'birthdate' => '1996-05-08',
            'phone' => '2221456488',
            'email' => 'user2@test.com',
            'password' => $password,
        ]);
        $user2->assignRole('user');
    }
}
