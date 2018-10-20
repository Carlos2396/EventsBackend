<?php

namespace Tests\Unit\Events;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\User;

class RetrieveEventTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test retrieve list of events
     */
    public function testRetrieveEventsList()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $events = Event::all();

        $response = $this->withHeaders(self::$headers)->get(route('events.list'));

        $response
            ->assertStatus(200)
            ->assertJson($events->toArray());
    }

    /**
     * Test succesful retrieve existent Event
     */
    public function testRetrieveExistentEvent()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $event = Event::first();

        $response = $this->withHeaders(self::$headers)->get(route('events.show', $event->id));

        $response
            ->assertStatus(200)
            ->assertJson($event->toArray());
    }

    /**
     * Test fail retrieve Event with no existent Event
     */
    public function testRetrieveNonExistentArticle()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $response = $this->withHeaders(self::$headers)->get(route('events.show', 0));

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }
}
