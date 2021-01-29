<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\UpgradeCode;
use Database\Seeders\RolesSeeder;
use Illuminate\Support\Facades\DB;

class AdminPanelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use RefreshDatabase, DatabaseMigrations;

    public function setUp(): void {
        parent::setUp();
        $this->seed(RolesSeeder::class);
    }

    public function test_admins_can_view_admin_page() {
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $response = $this->get('/admin/');

        $response->assertStatus(200);
    }

    public function test_non_admins_cannot_view_admin_page() {
        $this->be($admin = User::factory(['role_id'=>2])->create());
        $response = $this->get('/admin/');

        $response->assertRedirect('');
    }

    public function test_admin_can_ban_user()
    {
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $user = User::factory(['role_id'=>1])->create();
        $response = $this->post('/admin/ban/'.$user->id);

        $this->assertDatabaseHas('users', ['id'=> $user->id, 'banned'=>1]);
    }

    public function test_non_admin_cannot_ban_user()
    {
        $this->be(User::factory(['role_id'=>2])->create());
        $user = User::factory(['role_id'=>1])->create();
        $response = $this->post('/admin/ban/'.$user->id);

        $this->assertDatabaseMissing('users', ['id'=> $user->id, 'banned'=>1]);
    }

    public function test_admin_can_view_all_users() {
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $users = User::factory(['role_id'=>2])->count(3)->create();
        $response = $this->post('/admin/listUsers');

        $response->assertSee($users[0]->name);
        $response->assertSee($users[1]->name);
        $response->assertSee($users[2]->name);
    }

    public function test_admin_can_view_listed_and_unlisted_videos() {
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $video1 = Video::factory(['listed'=>0])->create();
        $video2 = Video::factory(['listed'=>1])->create();
        $response = $this->post('/admin/listVideos');

        $response->assertSee($video1->title);
        $response->assertSee($video2->title);
    }

    public function test_admin_can_view_owners_of_listed_and_unlisted_videos() {
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $video1 = Video::factory(['user_id' => $user1->id, 'listed'=>0])->create();
        $video2 = Video::factory(['user_id' => $user2->id, 'listed'=>0])->create();
        $response = $this->post('/admin/listVideos');

        $response->assertSee($user1->name);
        $response->assertSee($user2->name);
    }

    public function test_non_admin_cannot_delete_any_users_video_with_admin_delete() {
        $this->be($admin = User::factory(['role_id'=>2])->create());
        $user = User::factory()->create();
        $video = Video::factory(['user_id' => $user->id])->create();
        $response = $this->post('/admin/deleteVideo/'.$video->id);

        $response->assertRedirect('');
    }

    public function test_admin_can_delete_any_users_video() {
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $user = User::factory()->create();
        $video = Video::factory(['user_id' => $user->id])->create();
        $response = $this->post('/admin/deleteVideo/'.$video->id);

        $this->assertDatabaseMissing('videos', ['id'=> $video->id]);
    }
}
