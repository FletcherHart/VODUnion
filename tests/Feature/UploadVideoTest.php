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
    use RefreshDatabase, DatabaseMigrations, WithFaker;

    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    public function test_non_authenticated_user_can_not_access_upload_page()
    {
        $response = $this->get('/upload');

        $response->assertStatus(302);
    }

    public function test_authenticated_user_with_viewer_role_can_not_upload_video()
    {
        Storage::fake('videos');
        $this->be($user = User::factory(['role_id'=>1])->create());
        $response = $this->post('/upload', ['video' => '']);

        $response->assertStatus(403);
    }

    public function test_authenticated_user_with_viewer_role_sees_upgrade_page_when_accessing_upload_page()
    {
        $this->be($user = User::factory(['role_id'=>1])->create());
        $response = $this->get('/upload');

        $response->assertRedirect('upgrade');
    }

    public function test_deny_upload_if_more_than_20gb_already_stored() {

        Video::factory(['sizeKB' => 20000000001])->create();

        $this->be($user = User::factory(['role_id'=>2])->create());
        $file = UploadedFile::fake()->create('vid.mp4', 20, 'video/mp4');
        $video = ['video' => $file, 
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'listed' => true];
        $response = $this->post('/upload', $video);

        $response->assertSessionHasErrors(['storage']);
    }

    public function test_deny_upload_if_video_greater_than_2gb_in_size() {
        $this->be($user = User::factory(['role_id'=>2])->create());
        $file = UploadedFile::fake()->create('vid.mp4', 2000000001, 'video/mp4');
        $video = ['video' => $file, 
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'listed' => true];
        $response = $this->post('/upload', $video);

        $response->assertSessionHasErrors(['video']);
    }

    public function test_deny_if_file_is_not_of_type_video() {
        $this->be($user = User::factory(['role_id'=>2])->create());
        $file = UploadedFile::fake()->create('vid.jpg', 20, 'image/jpeg');
        $video = ['video' => $file, 
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'listed' => true];
        $response = $this->post('/upload', $video);

        $response->assertSessionHasErrors(['video']);
    }

    public function test_authenticated_user_with_uploader_role_can_upload_video()
    {
        $path = Storage::fake('public/videos');
        $this->be($user = User::factory(['role_id'=>2])->create());
        $file = UploadedFile::fake()->create('vid.mp4', 50, 'video/mp4');
        $response = $this->post('/upload', ['video' => $file, 
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'listed' => true]);

        Storage::assertExists("public/videos/" . $file->hashName());
    }

    public function test_successful_upload_adds_video_data_to_database() {
        $this->be($user = User::factory(['role_id'=>2])->create());
        $file = UploadedFile::fake()->create('vid.mp4', 50, 'video/mp4');
        $video = ['video' => $file, 
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'listed' => true];
        $response = $this->post('/upload', $video);

        $this->assertDatabaseHas('videos', [
            'title' => $video['title'],
            'description' => $video['description'],
            'user_id' => $user->id,
            'sizeKB' => $file->getSize()
        ]);
    }

    public function test_user_cannot_upload_more_than_3_videos() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        Video::factory(['user_id' => $user->id])->count(3)->create();

        $file = UploadedFile::fake()->create('vid.mp4', 50, 'video/mp4');

        $video = ['video' => $file, 
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'listed' => true];

        $response = $this->post('/upload', $video);

        $response->assertSessionHasErrors(['userVideos']);
    }
}
