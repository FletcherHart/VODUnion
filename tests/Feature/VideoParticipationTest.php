<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;

class VideoParticipationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_users_can_not_make_comment_on_video() {
        $response = $this->postJson('/video/1/comment', []);
        $response->assertStatus(401);
    }

    public function test_auth_user_can_make_comment_on_video() {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();
        $comment = Comment::factory()->make();
        $response = $this->postJson('/video/'.$video->id .'/comment', $comment->toArray());

        $this->get("/video/".$video->id)
            ->assertSee($comment->text);
    }

}