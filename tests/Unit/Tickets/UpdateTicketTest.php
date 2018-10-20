<?php

namespace Tests\Unit\Articles;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Ticket;

class UpdateticketTest extends TestCase 
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test update successful
     */
    public function testUpdateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $ticket = Ticket::first();
        $ticket->code = 'fhuehgkeweje34';
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('tickets.update', $ticket->id),
                $ticket->toArray()
            );

        $response
            ->assertStatus(200)
            ->assertJson(collect($ticket)->except('updated_at')->toArray());
    }

    /**
     * Test update fail with null fields
     */
    public function testUpdateNullFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $ticket = Ticket::first();
        $ticket->code = null;
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('tickets.update', $ticket->id),
                $ticket->toArray()
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'code' => ['The code field is required.'],
                ]
            ]);
    }

    /**
     * Test update fail with wrong type field
     */
    public function testUpdateWrongTypeField()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $ticket = Ticket::first();
        $ticket->code = 123456;
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('tickets.update', $ticket->id),
                $ticket->toArray()
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
     * Test update fail with non-existing Ticket
     */
    public function testUpdateNonExistingTicket() 
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $ticket = Ticket::all()->last();
        $ticket->code = "gjbnejgrebs";

        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('tickets.update', $ticket->id + 1),
                $ticket->toArray()
            );

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }
}