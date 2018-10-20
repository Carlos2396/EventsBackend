<?php

namespace Tests\Unit\Answers;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Answer;

class CreateAnswersTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test fail create Answer with null fields
     */
    public function testCreateNullFields()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $answer = [
            'answer' => null,
            'user_id' => 2,
            'extra_id' => 1
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('answers.store'),
                 $answer
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'answer' => ['The answer field is required.']
                ]
            ]);
    }

    /**
     * Test fail create Answer with non registered user (in the event)
     */
    public function testCreateInvalidUser()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $answer = [
            'answer' => 'Valid answer!',
            'user_id' => 1,
            'extra_id' => 1
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('answers.store'),
                 $answer
            );


        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'user_id' => ['The user is not registered in this event.']
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

        $answer = [
            'answer' => 'XL',
            'extra_id' => 1,
            'user_id' => 3
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('answers.store'),
                 $answer
            );

        $response
            ->assertStatus(201)
            ->assertJson($answer);
    }

}
