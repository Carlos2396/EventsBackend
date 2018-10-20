<?php

namespace Tests\Unit\Extras;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateExtraTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test fail create Article with null fields
     */
    public function testCreateNullFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $extra = [
            'text' => 'Es el extra',
            'event_id' => null
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('extras.store'),
                 $extra
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'event_id' => ['The event id field is required.']
                ]
            ]);
    }

    /**
     * Test successful create Extra
     *
     * @return void
     */
    public function testCreateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $extra = [
            'text' => 'Ok text',
            'event_id' => 1
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('extras.store'),
                 $extra
            );

        $response
            ->assertStatus(201)
            ->assertJson($extra);
    }
}
