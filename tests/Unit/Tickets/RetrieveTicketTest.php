<?php

namespace Tests\Unit\Tickets;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Ticket;

class TicketRetrieveTest extends TestCase 
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test retrieve list of tickets
     */
    public function testRetrieveTicketsList()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $tickets = Ticket::all();

        $response = $this->withHeaders(self::$headers)->get(route('tickets.list'));

        $response
            ->assertStatus(200)
            ->assertJson($tickets->toArray());
    }

    /**
     * Test succesful retrieve existent Ticket
     */
    public function testRetrieveExistentTicket()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $ticket = Ticket::first();

        $response = $this->withHeaders(self::$headers)->get(route('tickets.show', $ticket->id));

        $response
            ->assertStatus(200)
            ->assertJson($ticket->toArray());
    }

    /**
     * Test fail retrieve non-existent Ticket
     */
    public function testRetrieveNonExistentTicket()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $ticekt = Ticket::all()->last();

        $response = $this->withHeaders(self::$headers)->get(route('ticekts.show', $ticekt->id + 1));

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }
}