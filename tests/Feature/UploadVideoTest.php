<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Video;
use App\Models\Comment;
use Tests\TestCase;

class UploadVideoTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    public function test_non_authenticated_user_can_not_access_upload_page()
    {
        $response = $this->get('/upload');

        $response->assertStatus(302);
    }

    public function test_authenticated_user_with_uploader_role_can_upload_video()
    {
        Storage::fake('videos');
        $this->be($user = User::factory(['role_id'=>2])->create());
        $file = UploadedFile::fake()->create('vid.mp4', 50, 'video/mp4');
        $response = $this->post('/upload', ['video' => $file]);

        Storage::assertExists("videos/" . $file->hashName());
    }

    public function test_authenticated_user_with_viewer_role_can_not_upload_video()
    {
        Storage::fake('videos');
        $this->be($user = User::factory(['role_id'=>1])->create());
        $file = UploadedFile::fake()->create('vid.mp4', 50, 'video/mp4');
        $response = $this->post('/upload', ['video' => $file]);

        $response->assertStatus(403);
    }
}
