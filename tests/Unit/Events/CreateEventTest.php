<?php

namespace Tests\Unit\Events;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class CreateEventTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * Test fail create Event 
     */
    public function testFailEvent()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $event = [
            'name' => null,
            'starts' => Carbon::create(2018, 8, 12, 12, 0, 0)->toDateTimeString(),
            'end' => Carbon::create(2018, 8, 11, 12, 0, 0)->toDateTimeString(),
            'registration_start' => 'lol',
            'registration_end' => '',
            'image' => '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678900',
            'desciption' => 'lol One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin. He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff sections. The bedding was hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What s happened to me?" he thought. It wasn t a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls. A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture that he had recently cut out of an illustrated magazine and housed in a nice, gilded frame. It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower arm towards t',
            'organizer_id' => 0,
            'guest_capacity' => -9,
            'event_type' => 'Vermin',
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('events.store'),
                 $event
            );

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'Failed data validation',
                'errors' => [
                    'name' => ['The name field is required.'], 
                    'end' => ['The end must be a date after '.$event['starts'].'.'],
                    'registration_start' => ['The registration_start is not a valid date.'],
                    'registration_end' => ['The registration_end is not a valid date.'],
                    'image' => ['The image may not be greater than 100 characters.'],
                    'description' => ['The description may not be greater than 1000 characters.'],
                    'organizer_id' => ['The selected organizer_id is invalid.',],
                    'guest_capacity' => ['The guest_capacity must be greater than or equal 1.'],
                    'event_type' => ['The selected event_type is invalid.']
                ]
            ]);
    }

    /**
     * Test successful create Event
     *
     * @return void
     */
    public function testCreateSuccessful()
    {
        parent::withoutMiddleware(Helper::$middlewares);

        $event = [
            'name' => 'User 1',
            'starts' => Carbon::create(2018, 8, 12, 12, 0, 0)->toDateTimeString(),
            'end' => Carbon::create(2018, 8, 13, 12, 0, 0)->toDateTimeString(),
            'registration_start' => Carbon::create(2018, 7, 12, 12, 0, 0)->toDateTimeString(),
            'registration_end' => Carbon::create(2018, 8, 11, 12, 0, 0)->toDateTimeString(),
            'image' => null,
            'desciption' => 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin. He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff sections. The bedding was hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What s happened to me?" he thought. It wasn t a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls. A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture that he had recently cut out of an illustrated magazine and housed in a nice, gilded frame. It showed a lady fitted out with a fur hat and fur boa who sat upright, raising a heavy fur muff that covered the whole of her lower arm towards t',
            'organizer_id' => 1,
            'guest_capacity' => 1000,
            'event_type' => 'type1',
        ];
        
        $response = $this
            ->withHeaders(self::$headers)
            ->json(
                'POST',
                route('events.store'),
                 $event
            );

        $response
            ->assertStatus(201)
            ->assertJson($event);
    }
}
