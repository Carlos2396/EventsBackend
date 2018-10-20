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
        $ticket->title = null;
        $article->body = null;
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('articles.update', $article->id),
                $article->toArray()
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'title' => ['The title field is required.'],
                    'body' => ['The body field is required.']
                ]
            ]);
    }
}