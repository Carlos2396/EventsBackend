<?php

namespace Tests\Unit\Events;

use Tests\TestCase;
use Tests\Helper;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteEventTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test fail delete as Event does not exist
     */
    public function testDeleteNonExistentEvent()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $response = $this->withHeaders(self::$headers)->delete(route('events.delete', 0));
        
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }

    /**
     * Test successful delete Event
     */
    public function testDeleteExistentArticle()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $event = Event::first();

        $response = $this->withHeaders(self::$headers)->delete(route('events.delete', $event->id));
        
        $response->assertStatus(204);
    }
}
