<?php

namespace Tests\Unit\Users;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\User;

class RetrieveUserTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test retrieve list of users
     */
    public function testRetrieveUsersList()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $users = User::all();

        $response = $this->withHeaders(self::$headers)->get(route('users.list'));

        $response
            ->assertStatus(200)
            ->assertExactJson($users->toArray());
    }

    /**
     * Test succesful retrieve existent User
     */
    public function testRetrieveExistentUser()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $user = User::first();

        $response = $this->withHeaders(self::$headers)->get(route('users.show', $user->id));

        $response
            ->assertStatus(200)
            ->assertExactJson($user->toArray());
    }

    /**
     * Test fail retrieve Article with no existent Article
     */
    public function testRetrieveNonExistentUser()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $user = User::all()->last();

        $response = $this->withHeaders(self::$headers)->get(route('users.show', $user->id + 1));

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }
}
