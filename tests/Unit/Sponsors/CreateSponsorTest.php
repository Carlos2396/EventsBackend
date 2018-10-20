<?php

namespace Tests\Unit\Sponsors;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateSponsorTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test fail create Sponsor 
     */
    public function testCreateUnsuccesfull()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $sponsor = [
            'name' => null,
            'image' => '01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890',
            'event_id' => 0
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('sponsors.store'),
                 $sponsor
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
     * Test successful create Sponsor
     *
     * @return void
     */
    public function testCreateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $sponsor = [
            'name' => "Play Boy",
            'image' => null,
            'event_id' => 1
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('sponsors.store'),
                 $sponsor
            );

        $response
            ->assertStatus(201)
            ->assertJson($sponsor);
    }
}
