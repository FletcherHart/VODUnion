<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    public function setUp():void {
        parent::setUp();
        $this->seed();
        //Must use actual video
        $this->video = Video::factory(['listed'=>true, 'videoID' => '384f01536b5c4a6e9378d75b541cb436'])->create();
    }

    public function test_unauthenticated_users_can_not_make_comment_on_video() {
        $response = $this->postJson('/video/1/comment', ["text" => 'test text']);
        $response->assertStatus(401);
    }

    public function test_auth_user_can_make_comment_on_video() {
        $this->be($user = User::factory()->create());

        $comment = $this->faker->sentence;
        $response = $this->post('/video/'.$this->video->id .'/comment', ["text" => $comment]);

        $this->get("/video/".$this->video->id)
            ->assertSee($comment);
    }

}