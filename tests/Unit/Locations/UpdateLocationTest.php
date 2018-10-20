<?php

namespace Tests\Unit\Locations;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Location;

class UpdateLocationTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test successful update Location
     */
    public function testUpdateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $location = Location::first();
        $location->name = 'Nueva locacion';
        $location->address = 'Calle siempre viva';
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('locations.update', $location->id),
                $location->toArray()
            );

        $response
            ->assertStatus(200)
            ->assertJson(collect($location)->except('updated_at')->toArray());
    }


    /**
     * Test failed update on non-existent Location
     */
    public function testUpdateNonExisting() 
    {
        $location = Location::all()->last();
        $location->name = 'Nueva locación';
        $location->address = 'Calle siempre viva ';

        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('locations.update', $location->id + 1),
                $location->toArray()
            );

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
        ]);
    }


    /**
     * Test failed update on null/missing attributes
     */
    public function testUpdateNullOrMissingFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $location = Location::first();
        $location->event_id = null;
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('locations.update', $location->id),
                $location->toArray()
        );

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'event_id' => ['The event id field is required.'],
                ]
            ]);
    }

    /**
     * Test failed update on wrong type attributes
     */
    public function testUpdateWrongTypeFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $location = Location::first();
        $location->lat = '4p556';
        $location->lng = '5=440.4';

        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('locations.update', $location->id),
                $location->toArray()
        );

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'lat' => ['The lat must be a number.'],
                    'lng' => ['The lng must be a number.'],
                ]
            ]);
    }
}