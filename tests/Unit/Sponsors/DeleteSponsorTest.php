<?php

namespace Tests\Unit\Sponsors;

use Tests\TestCase;
use Tests\Helper;

use App\Models\Sponsor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteSponsorTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test fail delete a sponsor does not exist
     */
    public function testDeleteNonExistentSponsor()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $response = $this->withHeaders(self::$headers)->delete(route('sponsors.delete', 0));
        
        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }

    /**
     * Test successful delete Sponsor
     */
    public function testDeleteExistentSponsor()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $sponsor = Sponsor::first();

        $response = $this->withHeaders(self::$headers)->delete(route('sponsors.delete', $sponsor->id));
        
        $response->assertStatus(204);
    }
}
