<?php

namespace Tests\Unit\Tickets;

use Tests\TestCase;
use Tests\Helper;
use App\User;
use App\Models\Event;
use App\Models\Ticket;


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
            'code' => 'egheiuhewuih'
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
     * Test fail create Ticket with null or missing fields
     */
    public function testCreateNullFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::first();
        $event = Event::first();
        
        $data = [
            'event_id' => $event -> id,
            'userid' => $user -> id,
            'code' => null
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
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'user_id' => ['The user_id field must be present.'],
                    'code' => ['The code field is required.'],
                ]
            ]);
    }


    /**
     * Test fail create Ticket with a wrong type field
     */
    public function testCreateWrongType()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::first();
        $event = Event::first();
        
        $data = [
            'event_id' => $event -> id,
            'user_id' => $user -> id,
            'code' => 355434
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
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'code' => ['The code must be a string.']
                ]
            ]);
    }

    /**
     * Test fail create Ticket with repeated field(s)
     */
    public function testCreateUnique()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $user = User::first();
        $event = Event::first();
        
        $ticket = Ticket::first();

        $data = [
            'event_id' => $event -> id,
            'user_id' => $user -> id,
            'code' => $ticket -> code
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
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'code' => ['The code has already been taken.']
                ]
            ]);
    }
}