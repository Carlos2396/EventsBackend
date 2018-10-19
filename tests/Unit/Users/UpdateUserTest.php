<?php

namespace Tests\Unit\Users;

use Tests\TestCase;
use Tests\Helper;
use Carbon\Carbon;
use App\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateUserTest extends TestCase
{
    use DatabaseTransactions;
    
    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test successfully create user
     */
    public function testSuccessfulUpdateUser()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::first();
        $user->firstname = "New name";
        $user->firstname = "New lastname";
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('users.update', $user->email),
                $user->toArray()
            );

        $data = $user->toArray();
        unset($data['updated_at']);

        $response
            ->assertStatus(200)
            ->assertJson($data);
    }

    /**
     * Test fail create Article with a too long title
     */
    public function testFailedRegisterUser()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $user = User::first();
        $user->firstname = null;
        $user->lastname = 'Too long last name it, cant be longer than a 100 characters and this is definitely larger than a 100 characters.';
        $user->phone = '222145487a';

        $data = $user->toArray();
        $data['birthdate'] = '15/78/1554';
        $data['email'] = 'testuser';
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('users.update', $user->email),
                $data
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
                    'phone' => ['The phone must be a number.']
                ]
            ]);
    }
}
