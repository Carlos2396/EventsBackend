<?php

namespace Tests\Unit\Locations;

use Tests\TestCase;
use Tests\Helper;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteLocationTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test successful delete 
     */
    public function testDeleteExistent()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $location = Location::first();

        $response = $this->withHeaders(self::$headers)->delete(route('locations.delete', $location->id));
        
        $response->assertStatus(204);
    }

    /**
     * Test fail delete on non-existent Location
     */
    public function testDeleteNonExistent()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $location = Location::all()->last();

        $response = $this->withHeaders(self::$headers)->delete(route('locations.delete', $location->id + 1));
        
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
        ]);
    }
}