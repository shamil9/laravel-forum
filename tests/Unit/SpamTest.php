<?php

namespace Tests\Unit;

use App\Middleware\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpamTest extends TestCase
{
    /** @test */
    function it_validates_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->check('Good reply'));
    }
}
