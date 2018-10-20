<?php

namespace Tests\Unit\Locations;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateLocationTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test successful create Location
     */
    public function testCreateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $location = [
            'event_id' => 1,
            'name' => 'Itesm Mty',
            'address' => 'Calle pro',
            'lat' => 57.43,
            'lng' => 46.67
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('locations.store'),
                 $location
            );

        $response
            ->assertStatus(201)
            ->assertJson($location);
    }

    /**
     * Test fail creation on null/missing attributes
     */
    public function testCreateNullOrMissingFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $location = [
            'event_id' => null,
            'name' => 'Itesm Mty',
            'addresss' => 'Calle pro',
            'lat' => 57.43,
            'lng' => 46.67
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('locations.store'),
                 $location
            );

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'event_id' => ['The event_id field is required.'],
                    'address' => ['The address field must be present.'],
                ]
            ]);
    }

    /**
     * Test fail creation on wrong type attributes
     */
    public function testCreateWrongTypeFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $location = [
            'event_id' => 1,
            'name' => 'Itesm Mty',
            'address' => 'Calle pro',
            'lat' => '57.43',
            'lng' => '46.67'
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('locations.store'),
                 $location
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