<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;

class CommentTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    public function test_reply_has_owner()
    {
        $reply = Comment::factory()->create();

        $this->assertInstanceOf('App\Models\User', $reply->user);
    }
}
