<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use App\Reply;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_checks_for_invalid_words()
    {
        $spam = new Spam();

        $this->assertFalse($spam->check('Good reply'));

        $this->expectException('Exception');

        $spam->check('yahoo customer support');
    }

    /** @test */
    public function it_checks_for_key_repetition()
    {
        $spam = new Spam();

        $this->expectException('Exception');

        $spam->check('Hey ssssss');
    }

    /** @test */
    public function it_checks_for_replies_spam()
    {
        $firstReply = factory(Reply::class)->create();
        $secondReply = factory(Reply::class)->create(
            ['created_at' => Carbon::now()->subMinute()]
        );

        $this->assertTrue($firstReply->isJustPosted());
        $this->assertFalse($secondReply->isJustPosted());
    }
}
