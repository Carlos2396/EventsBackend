<?php

namespace Tests\Unit\Tickets;

use Tests\TestCase;
use Tests\Helper;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteTicketTest extends TestCase 
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test succesful elimination 
     */
    public function testDeleteExistentTicket()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $ticket = Ticket::first();

        $response = $this->withHeaders(self::$headers)->delete(route('tickets.delete', $ticket->id));
        
        $response->assertStatus(204);
    }

    /**
     * Test fail delete of non existent ticket
     */
    public function testDeleteNonExistentTicket()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $ticket = Ticket::all()->last();

        $response = $this->withHeaders(self::$headers)->delete(route('tickets.delete', $article->id + 1));
        
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }

    /**
     * Test fail delete with wrong attribute type
     */
    public function testDeleteWrongFieldType()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $ticket = Ticket::all()->last();

        $response = $this->withHeaders(self::$headers)->delete(route('tickets.delete', "1"));
        
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'id' => 'The id must be a number.'
            ]);
    }
}