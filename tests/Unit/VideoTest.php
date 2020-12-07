<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Video;
use App\Models\User;

class VideoTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void {
        parent::setUp();
        $this->video = Video::factory()->create();
    }

    public function test_video_has_owner()
    {
        $this->assertInstanceOf('App\Models\User', $this->video->user);
    }

    public function test_video_can_add_reply() {
        $this->video->addComment([
            'text' => 'foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->video->comments);
    }
}
