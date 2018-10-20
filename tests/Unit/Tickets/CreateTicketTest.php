<?php

namespace Tests\Unit\Tickets;

use Tests\TestCase;
use Tests\Helper;
use App\User;
use App\Models\Event;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTicketTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Succesful insertion
     */
    public function testCreate() 
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::first();
        $event = Event::first();

        $data = [
            'event_id' => $event -> id,
            'user_id' => $user -> id,
        ];

        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('tickets.store'),
                $data
            );

        $response
            ->assertStatus(201)
            ->assertJson($data);
    }

    /**
     * Failed insertion on non existing fk
     */
    public function testFailedCreate() 
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::all()->last();
        $event = Event::first();

        $data = [
            'event_id' => $event -> id,
            'user_id' => $user -> id + 1,
        ];

        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('tickets.store'),
                $data
        );

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'user_id' => ['The selected user id is invalid.'],
                ]
            ]);
    }
}