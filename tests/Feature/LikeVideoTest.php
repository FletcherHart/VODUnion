<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Video;
use App\Models\LikeVideo;
use Tests\TestCase;

class LikeVideoTest extends TestCase
{
    use RefreshDatabase, WithFaker, DatabaseMigrations;
    
    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_user_cannot_like_video()
    {
        $video = Video::factory()->create();
        $response = $this->post('/video/' . $video->id . '/like');

        $response->assertRedirect('login');
    }

    public function test_auth_user_can_like_video()
    {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();
        $response = $this->post('/video/' . $video->id . '/like');

        $this->assertDatabaseHas('like_videos', ['user_id' => $user->id, 'video_id' => $video->id]);
    }

    public function test_already_liked_video_unlikes_video()
    {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();
        $like = new LikeVideo;
        $like->user_id = $user->id;
        $like->video_id = $video->id;
        $like->save();
        $response = $this->post('/video/' . $video->id . '/like');

        $this->assertDatabaseMissing('like_videos', ['user_id' => $user->id, 'video_id' => $video->id]);
    }

    public function test_deleted_video_deletes_likes() {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();
        $like = new LikeVideo;
        $like->user_id = $user->id;
        $like->video_id = $video->id;
        $like->save();

        $video->delete();
        $this->assertDatabaseMissing('like_videos', ['user_id' => $user->id, 'video_id' => $video->id]);
    }

    public function test_unauthenticated_user_cannot_dislike_video()
    {
        $video = Video::factory()->create();
        $response = $this->post('/video/' . $video->id . '/dislike');

        $response->assertRedirect('login');
    }

    public function test_auth_user_can_dislike_video()
    {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();
        $response = $this->post('/video/' . $video->id . '/dislike');

        $this->assertDatabaseHas('like_videos', ['user_id' => $user->id, 'video_id' => $video->id, 'like' => 0]);
    }

    public function test_already_disliked_video_undislikes_video()
    {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();
        $dislike = new LikeVideo;
        $dislike->user_id = $user->id;
        $dislike->video_id = $video->id;
        $dislike->like = 0;
        $dislike->save();
        $response = $this->post('/video/' . $video->id . '/dislike');

        $this->assertDatabaseMissing('like_videos', ['user_id' => $user->id, 'video_id' => $video->id]);
    }

    public function test_liked_video_switches_to_dislike_if_disliked() {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();
        $like = new LikeVideo;
        $like->user_id = $user->id;
        $like->video_id = $video->id;
        $like->save();
        $response = $this->post('/video/' . $video->id . '/dislike');

        $this->assertDatabaseHas('like_videos', ['user_id' => $user->id, 'video_id' => $video->id, 'like' => 0]);
    }

    public function test_disliked_video_switches_to_liked_if_liked() {
        $this->be($user = User::factory()->create());

        $video = Video::factory()->create();
        $dislike = new LikeVideo;
        $dislike->user_id = $user->id;
        $dislike->video_id = $video->id;
        $dislike->like = 0;
        $dislike->save();
        $response = $this->post('/video/' . $video->id . '/like');

        $this->assertDatabaseHas('like_videos', ['user_id' => $user->id, 'video_id' => $video->id, 'like' => 1]);
    }

}
