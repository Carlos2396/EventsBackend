<?php

namespace Tests\Unit\Extras;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Extra;

class DeleteExtraTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test fail delete as Extra does not exist
     */
    public function testDeleteNonExistentExtra()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $extra = Extra::all()->last();

        $response = $this->withHeaders(self::$headers)->delete(route('extras.delete', $extra->id + 1));
        
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }

    /**
     * Test successful delete Extra
     */
    public function testDeleteExistentExtra()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $extra = Extra::first();

        $response = $this->withHeaders(self::$headers)->delete(route('extras.delete', $extra->id));
        
        $response->assertStatus(204);
    }
    
}
