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
use App\Models\LikeVideo;

class VideoHistoryTest extends TestCase
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
    public function test_authenticated_user_can_see_history_page()
    {
        $this->be($user = User::factory()->create());
        $response = $this->get('/history');

        $response->assertStatus(200);
    }

    public function test_non_auth_user_redirected_to_login()
    {
        $response = $this->get('/history');

        $response->assertStatus(302);
    }

    public function test_video_added_to_history_for_auth_user()
    {
        $this->be($user = User::factory()->create());
        $video = Video::factory()->create();
        $response = $this->get('/video/'.$video->id);

        $this->assertDatabaseHas('watched_videos', ['video_id' => $video->id, 'user_id' => $user->id]);
    }
}
