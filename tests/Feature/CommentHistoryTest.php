<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;
use App\Models\LikeVideo;

class CommentHistoryTest extends TestCase
{

    use RefreshDatabase, WithFaker, DatabaseMigrations;
    
    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_authenticated_user_can_see_comment_history_page()
    {
        $this->be($user = User::factory()->create());
        $response = $this->get('/history/comments');

        $response->assertStatus(200);
    }


    public function test_can_see_own_comments()
    {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();

        $comment = Comment::factory(['user_id' => $user->id, 'video_id' => $video->id])->create();

        $response = $this->get('/history/comments');

        $response->assertSee($comment->text);
    }

    public function test_can_see_video_comment_is_from()
    {
        $this->be($user = User::factory()->create());

        $uploader = User::factory()->create();

        $video = Video::factory(['user_id' => $uploader->id])->create();

        $comment = Comment::factory(['user_id' => $user->id, 'video_id' => $video->id])->create();

        $response = $this->get('/history/comments');

        $response->assertSee($video->title);
        $response->assertSee($uploader->name);
    }
}
