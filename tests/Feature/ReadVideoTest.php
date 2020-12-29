<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Video;
use App\Models\Comment;

class ReadVideoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;

    public function setUp(): void {
        parent::setUp();
        $this->seed();
        $this->video = Video::factory(['listed'=>true])->create();
    }

    public function test_user_can_browse_videos()
    {

        $response = $this->get('/');

        $response->assertSee($this->video->title);
    }

    public function test_user_can_view_single_video() {
        $response = $this->get('video/' . $this->video->id);

        $response->assertSee($this->video->title);
    }

    public function test_user_can_read_replies_associated_with_video() {
        $comment = Comment::factory()->create(["video_id" => $this->video->id]);
        
        $response = $this->get('video/' . $this->video->id);

        $response->assertSee($comment->text);
    }
}
