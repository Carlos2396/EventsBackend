<?php

namespace Tests\Unit\Users;

use Tests\TestCase;
use Tests\Helper;
use Carbon\Carbon;

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
        $user = User::first();
        $data = [
            'old_password' => 'secret',
            'password' => 'secreto',
            'password_confirmation' => 'secreto'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('users.changePassword', $user->id),
                $data
            );

        $response->assertStatus(204);
    }

    /**
     * Test fail create Article with a too long title
     */
    public function testFailedRegisterUser()
    {
        $user = User::first();
        $data = [
            'old_password' => 'secreto',
            'password' => 'secreto',
            'password_confirmation' => 'secreto'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('users.changePassword', $user->id),
                $data
            );

        $response
            ->assertStatus(401)
            ->assertExactJson([
                'message' => 'Incorrect old password.'
            ]);
    }
}
