<?php

namespace Tests\Unit\Locations;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;
use App\User;

class RetrieveLocationTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test retrieve list of location
     */
    public function testRetrieveLocationList()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $locations = Location::all();

        $response = $this->withHeaders(self::$headers)->get(route('locations.list'));

        $response
            ->assertStatus(200)
            ->assertExactJson($locations->toArray());
    }

    /**
     * Test succesful retrieve existent Location
     */
    public function testRetrieveExistentLocation()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $location = Location::first();

        $response = $this->withHeaders(self::$headers)->get(route('locations.show', $location->id));

        $response
            ->assertStatus(200)
            ->assertExactJson($location->toArray());
    }

    /**
     * Test fail retrieve of non existent Location
     */
    public function testRetrieveNonExistentLocation()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $location = Location::all()->last();

        $response = $this->withHeaders(self::$headers)->get(route('locations.show', $location->id + 1));

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }
}