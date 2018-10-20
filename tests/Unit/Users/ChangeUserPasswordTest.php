<?php

namespace Tests\Unit\Users;

use Tests\TestCase;
use Tests\Helper;
use Carbon\Carbon;
use App\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChangeUserPasswordTest extends TestCase
{
    use DatabaseTransactions;
    
    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test successfully create user
     */
    public function testSuccessfulChangeuserPassword()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::first();
        $data = [
            'old_password' => 'secret',
            'password' => 'secreto',
            'password_confirmation' => 'secreto'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('users.changePassword', $user->email),
                $data
            );

        $response->assertStatus(204);
    }

    /**
     * Test fail change user password, old password is incorrect
     */
    public function testFailedChangeUserPassword()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::first();
        $data = [
            'old_password' => 'secreto',
            'password' => 'secreto',
            'password_confirmation' => 'secreto'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('users.changePassword', $user->email),
                $data
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Incorrect old password.'
            ]);
    }
}
