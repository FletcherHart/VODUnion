<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
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

        $this->assertDatabaseHas('banned_users', ['user_id'=> $user->id]);
    }

    public function test_non_admin_cannot_ban_user()
    {
        $this->be(User::factory(['role_id'=>2])->create());
        $user = User::factory(['role_id'=>1])->create();
        $response = $this->post('/admin/ban/'.$user->id);

        $this->assertDatabaseMissing('banned_users', ['user_id'=> $user->id]);
    }

    public function test_admin_can_view_all_users() {
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $users = User::factory(['role_id'=>2])->count(3)->create();
        $response = $this->post('/admin/listUsers'.$user->id);

        $response->assertSee($users[0]->name);
        $response->assertSee($users[1]->name);
        $response->assertSee($users[2]->name);
    }

}
