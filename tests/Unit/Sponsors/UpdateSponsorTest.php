<?php

namespace Tests\Unit\Sponsors;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Sponsor;

class UpdateSponsorTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test invalid update
     *
     * @return void
     */
    public function testInvalidUpdate()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $sponsor = Sponsor::first();
        $sponsor->name = null;
        $sponsor->image = "012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789";
        $sponsor->event_id = 0;

        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('sponsors.update', $sponsor->id),
                $sponsor->toArray()
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'image' => ['The image may not be greater than 100 characters.'],
                    'event_id' => ['The selected event id is invalid.']
                ]
            ]);
    }

    /**
     * Test succesfull update
     *
     * @return void
     */
    public function testSuccesfulUpdate()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $sponsor = Sponsor::first();
        $sponsor->name = 'Ok name';
        $sponsor->image = null;
        $sponsor->event_id = 1;
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'PUT',
                route('sponsors.update', $sponsor->id),
                $sponsor->toArray()
            );

        $response
            ->assertStatus(200)
            ->assertJson(collect($sponsor)->except('updated_at')->toArray());
    }
}
