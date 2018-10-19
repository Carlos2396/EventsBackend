<?php

namespace Tests\Unit\Users;

use Tests\TestCase;
use Tests\Helper;
use Carbon\Carbon;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterUserTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test successfully create user
     */
    public function testSuccessfulRegisterUser()
    {
        $user = [
            'firstname' => 'User',
            'lastname' => 'Test',
            'email' => 'testuser@test.com',
            'alias' => 'Test alias',
            'birthdate' => '1996-10-15',
            'gender' => 'male',
            'phone' => '2221454878',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('users.register'),
                $user
            );

        $user['birthdate'] = Carbon::new($user['birthdate']);
        $response
            ->assertStatus(201)
            ->assertJson($user);
    }

    /**
     * Test fail create Article with a too long title
     */
    public function testFailedRegisterUser()
    {
        $user = [
            'firstname2' => 'User',
            'lastname' => 'Too long last name it, cant be longer than a 100 characters and this is definitely larger than a 100 characters.',
            'email' => 'testuser',
            'alias' => 'Test alias',
            'birthdate' => '1996-10-15 15',
            'gender' => 'male',
            'phone' => '222145487a',
            'password' => 'secret',
            'password_confirmation' => 'secreto'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('users.register'),
                $user
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'firstname' => ['The firstname field is required.'],
                    'lastname' => ['The lastname may not be greater than 100 characters.'],
                    'email' => ['The email must be a valid email address.'],
                    'birthdate' => ['The birthdate is not a valid date.'],
                    'phone' => ['The phone must be a numeric.'],
                    'password' => ['The password confirmation does not match.']
                ]
            ]);
    }
}
