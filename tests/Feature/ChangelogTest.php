<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ChangelogTest extends TestCase
{

    use DatabaseMigrations, WithFaker, RefreshDatabase;

    public function setUp():void {
        parent::setUp();
        $this->seed();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_make_blog_post()
    {
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $title= $this->faker->sentence;
        $text = $this->faker->paragraph;
        $response = $this->post('/admin/changelog', ["title"=> $title,"text" => $text]);

        $this->assertDatabaseHas('changelogs', ["title"=> $title,'text' => $text]);
    }

    public function test_non_admin_users_cannot_make_blog_post()
    {
        $this->be($admin = User::factory(['role_id'=>2])->create());
        $title= $this->faker->sentence;
        $text = $this->faker->paragraph;
        $response = $this->post('/admin/changelog', ["title"=> $title,"text" => $text]);
        $this->assertDatabaseMissing('changelogs', ["title"=> $title,'text' => $text]);
    }

    public function test_only_admins_can_access_changelog_form() {
        //admins can access
        $this->be($admin = User::factory(['role_id'=>4])->create());
        $response = $this->get('/admin/changelog');
        $response->assertStatus(200);

        //non-admins cannot access
        $this->be($admin = User::factory(['role_id'=>2])->create());
        $response = $this->get('/admin/changelog');
        $response->assertStatus(302);
    }
}
