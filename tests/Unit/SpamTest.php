<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpamTest extends TestCase
{
    /** @test */
    function it_checks_for_invalid_words()
    {
        $spam = new Spam();

        $this->assertFalse($spam->check('Good reply'));

        $this->expectException('Exception');

        $spam->check("yahoo customer support");
    }

    /** @test */
    function it_checks_for_key_repetition()
    {
        $spam = new Spam();

        $this->expectException('Exception');

        $spam->check('Hey ssssss');
    }
}
