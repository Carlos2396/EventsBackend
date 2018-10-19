<?php

namespace Tests\Unit\Users;

use Tests\TestCase;
use Tests\Helper;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test fail delete as User does not exist
     */
    public function testDeleteNonExistentUser()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::all()->last();

        $response = $this->withHeaders(self::$headers)->delete(route('users.delete', $user->email.'.mx'));
        
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }

    /**
     * Test successful delete User
     */
    public function testDeleteExistentUser()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::first();

        $response = $this->withHeaders(self::$headers)->delete(route('users.delete', $user->email));
        
        $response->assertStatus(204);
    }
}
