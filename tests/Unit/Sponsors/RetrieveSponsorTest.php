<?php

namespace Tests\Unit\Sponsors;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use App\Models\Sponsor;
use App\User;

class RetrieveSponsorTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Test retrieve list of sponsors
     */
    public function testRetrieveSponsorsList()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $sponsors = Sponsor::all();

        $response = $this->withHeaders(self::$headers)->get(route('sponsors.list'));

        $response
            ->assertStatus(200)
            ->assertJson($sponsors->toArray());
    }

    /**
     * Test succesful retrieve existent sponsor
     */
    public function testRetrieveExistentSponsor()
    {
        parent::withoutMiddleware(Helper::$middlewares);
        
        $sponsor = Sponsor::first();

        $response = $this->withHeaders(self::$headers)->get(route('sponsors.show', $sponsor->id));

        $response
            ->assertStatus(200)
            ->assertJson($sponsor->toArray());
    }

    /**
     * Test fail retrieve Sponsor with no existent sponsor
     */
    public function testRetrieveNonExistentSponsor()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $response = $this->withHeaders(self::$headers)->get(route('sponsors.show', 0));

        $response
            ->assertStatus(404)
            ->assertExactJson([
                'message' => 'Resource not found'
            ]);
    }
}
