<?php

namespace Tests\Unit\Answers;

use Tests\TestCase;
use Tests\Helper;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Answer;

class RetrieveAnswersTest extends TestCase
{
    use DatabaseTransactions;

    static $headers = [
        'Accept' => 'application/json'
    ];

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
    
}
