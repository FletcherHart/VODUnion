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
    use DatabaseMigrations, WithFaker, RefreshDatabase;

    public function setUp():void {
        parent::setUp();
        $this->seed();
        //Must use actual video
        $this->video = Video::factory(['listed'=>true, 'videoID' => '384f01536b5c4a6e9378d75b541cb436'])->create();
    }

    public function test_unauthenticated_users_can_not_make_comment_on_video() {
        $response = $this->post('/video/'.$this->video->id .'/comment', ["text" => 'test text']);
        $response->assertStatus(302);
    }

    public function test_auth_user_can_make_comment_on_video() {
        $this->be($user = User::factory()->create());

        $comment = $this->faker->sentence;
        $response = $this->post('/video/'.$this->video->id .'/comment', ["text" => $comment]);

        $this->get("/video/".$this->video->id)
            ->assertSee($comment);
    }

    public function test_unauthenticated_users_cannot_delete_comments() {
        $comment = Comment::factory()->create();
        $response = $this->delete('/video/'.$this->video->id .'/comment/'.$comment->id);

        $response->assertStatus(302);
    }

    public function test_owner_can_delete_own_comment() {
        $this->be($user = User::factory()->create());

        $comment = Comment::factory(['user_id'=>$user->id])->create();
        $response = $this->delete('/video/'.$comment->video_id .'/comment/'.$comment->id);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_auth_user_cannot_delete_unowned_comments() {
        $user = User::factory(['id'=>50])->create();
        $this->be(User::factory(['id'=>100])->create());

        $comment = Comment::factory(['user_id'=>50])->create();
        $response = $this->delete('/video/'.$comment->video_id .'/comment/'.$comment->id);

        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    public function test_admins_can_delete_any_comment() {
        $user = User::factory(['id'=>50])->create();
        $this->be(User::factory(['id'=>100, 'role_id'=>4])->create());

        $comment = Comment::factory(['user_id'=>50])->create();
        $response = $this->delete('/video/'.$comment->video_id .'/comment/'.$comment->id);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_owner_of_video_can_delete_any_comment_on_video() {
        $user = User::factory(['id'=>50])->create();
        $this->be($owner = User::factory(['id'=>100, 'role_id'=>2])->create());

        $video = Video::factory(['user_id'=>$owner->id])->create();

        $comment = Comment::factory(['user_id'=>50, 'video_id'=>$video->id])->create();
        $response = $this->delete('/video/'.$comment->video_id .'/comment/'.$comment->id);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

}