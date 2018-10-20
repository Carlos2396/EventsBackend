<?php

namespace Tests\Unit\Events;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class CreateEventTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test fail create Event 
     */
    public function testFailEvent()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $event = [
            'name' => 'User 1',
            'starts' => Carbon::create(2030, 8, 12, 12, 0, 0)->toDateTimeString(),
            'end' => Carbon::create(2018, 8, 13, 12, 0, 0)->toDateTimeString(),
            'registration_start' => Carbon::create(2018, 7, 12, 12, 0, 0)->toDateTimeString(),
            'registration_end' => Carbon::create(2018, 8, 11, 12, 0, 0)->toDateTimeString(),
            'image' => null,
            'description' => 'description',
            'organizer_id' => 1,
            'guest_capacity' => 1000,
            'event_type' => 'type1',
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('events.store'),
                 $event
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'end' => ['The end must be a date after starts.'],
                    'starts' => ["The starts must be a date before end."]
                ]
            ]);
    }

    /**
     * Test successful create Event
     *
     * @return void
     */
    public function testCreateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $event = [
            'name' => 'User 1',
            'starts' => Carbon::create(2018, 8, 12, 12, 0, 0)->toDateTimeString(),
            'end' => Carbon::create(2018, 8, 13, 12, 0, 0)->toDateTimeString(),
            'registration_start' => Carbon::create(2018, 7, 12, 12, 0, 0)->toDateTimeString(),
            'registration_end' => Carbon::create(2018, 8, 11, 12, 0, 0)->toDateTimeString(),
            'image' => null,
            'description' => 'description',
            'organizer_id' => 1,
            'guest_capacity' => 1000,
            'event_type' => 'type1',
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('events.store'),
                 $event
            );

        $response
            ->assertStatus(201)
            ->assertJson($event);
    }
}
