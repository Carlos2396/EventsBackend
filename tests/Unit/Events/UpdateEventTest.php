<?php

namespace Tests\Unit\Events;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Event;
use Carbon\Carbon;

class UpdateEventTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test update fail with null fields successful
     *
     * @return void
     */
    public function testUnsuccesfullUpdate()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $event = Event::first();
        $event->name = null;
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('events.update', $event->id),
                $event->toArray()
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'name' => ['The name field is required.']
                ]
            ]);
    }

    /**
     * Test succesfull update
     *
     * @return void
     */
    public function testSuccessfulUpdate()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $event = Event::first();
        $event->name = 'Ok name';

        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('events.update', $event->id),
                $event->toArray()
            );

        $response
            ->assertStatus(200)
            ->assertJson(collect($event)->except('updated_at')->toArray());
    }
}
