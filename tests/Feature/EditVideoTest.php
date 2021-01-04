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
use App\Models\Comment;
use Tests\TestCase;

class EditVideoTest extends TestCase
{
    use RefreshDatabase, WithFaker, DatabaseMigrations;
    
    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_user_redirected_from_channel_page()
    {
        $response = $this->get('/channel');

        $response->assertRedirect('login');
    }

    public function test_authenticated_user_with_viewer_role_redirected_from_channel_page()
    {
        $this->be($user = User::factory(['role_id'=>1])->create());
        $response = $this->get('/channel');

        $response->assertRedirect('upgrade');
    }

    public function test_user_with_uploader_role_can_view_page() {
        $this->be($user = User::factory(['role_id'=>2])->create());
        $response = $this->get('/channel');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_see_their_uploaded_videos() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $videos = Video::factory(['user_id' => $user->id])->count(3)->create();

        $response = $this->get('/channel');

        $response->assertSee($videos->first()->title);
    }

    public function test_authenticated_user_can_not_edit_different_users_video_title() {
        $this->be($user = User::factory(['role_id'=>2])->create());
        $other_user = User::factory(['role_id'=>2])->create();

        $video = Video::factory(['user_id' => $other_user->id])->create();

        $sentence = $this->faker->sentence;

        $response = $this->post('/channel/'. $video->id, ['title' => $sentence]);

        $response->assertRedirect('/');
    }

    public function test_authenticated_user_can_edit_own_video_title() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id])->create();

        $sentence = $this->faker->sentence;

        $response = $this->post('/channel/'. $video->id, ['title' => $sentence]);

        $this->assertDatabaseHas('videos', ['id' => $video->id, 'title' => $sentence]);
    }

    public function test_authenticated_user_can_edit_own_video_description() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id])->create();

        $paragraph = $this->faker->paragraph;

        $response = $this->post('/channel/'. $video->id, ['description' => $paragraph]);

        $this->assertDatabaseHas('videos', ['id' => $video->id, 'description' => $paragraph]);
    }

    public function test_authenticated_user_can_add_own_video_thumbnail() {
        Storage::fake('public/thumbnails');
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id])->create();

        $image = UploadedFile::fake()->create('cat.jpg', 50, 'image/jpeg');

        $response = $this->post('/channel/'. $video->id, ['thumbnail' => $image]);

        // $this->assertDatabaseHas('videos', ['id' => $video->id, 'thumb' => $paragraph]);

        Storage::assertExists("public/thumbnails/" . $image->hashName());
    }

    public function test_authenticated_user_cannot_list_video_if_title_is_null_and_title_not_provided() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id, 'title' => null])->create();

        $response = $this->post('/channel/'. $video->id, ['list' => true]);

        $response->assertSessionHasErrors();
    }

    public function test_authenticated_user_cannot_list_video_if_stored_description_is_null_and_description_not_provided() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id, 'description' => null])->create();

        $response = $this->post('/channel/'. $video->id, ['list' => true]);

        $response->assertSessionHasErrors();
    }

    public function test_authenticated_user_can_list_video_if_stored_title_is_null_but_title_is_provided() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id, 'title' => null])->create();

        $sentence = $this->faker->sentence;

        $response = $this->post('/channel/'. $video->id, ['list' => true, 'title' => $sentence]);

        $this->assertDatabaseHas('videos', ['id' => $video->id, 'title' => $sentence, 'listed' => 1]);
    }

    public function test_authenticated_user_can_list_video_if_stored_description_is_null_but_description_is_provided() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id, 'description' => null])->create();

        $sentence = $this->faker->sentence;

        $response = $this->post('/channel/'. $video->id, ['list' => true, 'description' => $sentence]);

        $this->assertDatabaseHas('videos', ['id' => $video->id, 'description' => $sentence, 'listed' => 1]);
    }

    public function test_authenticated_user_can_list_video_if_title_is_not_provided_but_stored_title_is_not_null() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id])->create();

        $response = $this->post('/channel/'. $video->id, ['list' => true]);

        $this->assertDatabaseHas('videos', ['id' => $video->id, 'title' => $video->title, 'listed' => 1]);
    }

    public function test_authenticated_user_can_list_video_if_description_is_not_provided_but_stored_description_is_not_null() {
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id])->create();

        $response = $this->post('/channel/'. $video->id, ['list' => true]);

        $this->assertDatabaseHas('videos', ['id' => $video->id, 'description' => $video->description, 'listed' => 1]);
    }

    public function test_owner_can_delete_video() {
        //NOTE: does not test that video is deleted from Cloudflare, only from database.
        $this->be($user = User::factory(['role_id'=>2])->create());

        $video = Video::factory(['user_id' => $user->id])->create();

        $response = $this->delete('/channel/'. $video->id);

        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }

    public function test_user_other_than_owner_cannot_delete_video() {
        //NOTE: does not test that video is deleted from Cloudflare, only from database.
        $owner = User::factory(['role_id'=>2])->create();

        $video = Video::factory(['user_id' => $owner->id])->create();

        $this->be($user = User::factory(['role_id'=>2])->create());

        $response = $this->delete('/channel/'. $video->id);

        $this->assertDatabaseHas('videos', ['id' => $video->id]);
    }

}
