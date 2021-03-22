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

    public function test_authenticated_user_with_viewer_role_sees_upgrade_page_when_accessing_channel_page()
    {
        $this->be($user = User::factory(['role_id'=>1])->create());
        $response = $this->get('/channel');

        $response->assertRedirect('upgrade');
    }

    public function test_user_with_3_uploaded_videos_cannot_upload_more_videos() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        Video::factory(['user_id' => $user->id])->count(3)->create();

        $response = $this->get('/key');

        $response->assertSessionHasErrors(['deny']);
    }

    // public function test_user_can_upload_thumbnail_with_video() {
    //     //also doubles as manual raw video test
    //     Storage::fake('public/thumbnails');

    //     $this->be($user = User::factory(['role_id'=>2])->create());

    //     $file = UploadedFile::fake()->create('vid.mp4', 50, 'video/mp4');

    //     $image = UploadedFile::fake()->create('cat.jpg', 50, 'image/jpeg');

    //     $video = ['video' => $file, 
    //     'title' => $this->faker->sentence,
    //     'description' => $this->faker->paragraph,
    //     'listed' => true,
    //     'thumbnail' => $image,
    //     'raw' => true ];

    //     $response = $this->post('/upload', $video);

    //     Storage::assertExists("public/thumbnails/" . $file->hashName() . '.jpeg');
    // }

}
