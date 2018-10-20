<?php

namespace Tests\Unit\Extras;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Extra;

class RetrieveExtraTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test retrieve list of extras
     */
    public function testRetrieveExtrasList()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $extras = Extra::all();

        $response = $this->withHeaders(self::$headers)->get(route('extras.list'));

        $response
            ->assertStatus(200)
            ->assertExactJson($extras->toArray());
    }

    /**
     * Test succesful retrieve existent Extra
     */
    public function testRetrieveExistentExtra()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $extra = Extra::first();

        $response = $this->withHeaders(self::$headers)->get(route('extras.show', $extra->id));

        $response
            ->assertStatus(200)
            ->assertExactJson($extra->toArray());
    }

    /**
     * Test retrieving non-existent Extra
     */
    public function testRetrieveNonExistentExtra()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $extra = Extra::all()->last();

        $response = $this->withHeaders(self::$headers)->get(route('extras.show', $extra->id + 1));

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }
    
}
