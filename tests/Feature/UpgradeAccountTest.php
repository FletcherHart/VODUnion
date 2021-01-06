<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\UpgradeCode;
use Database\Seeders\RolesSeeder;
use Illuminate\Support\Facades\DB;

class UpgradeAccountTest extends TestCase
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

    public function test_user_without_key_does_not_change_role()
    {
        $this->be($user = User::factory(['role_id'=>1])->create());
        $response = $this->post('/upgrade');

        $this->assertDatabaseHas('users', ['id'=> $user->id, 'role_id'=>1]);
    }

    public function test_user_without_key_is_given_error()
    {
        $this->be($user = User::factory(['role_id'=>1])->create());
        $response = $this->post('/upgrade');

        $response->assertSessionHasErrors();
    }

    public function test_user_with_invalid_key_is_given_error()
    {
        $code = UpgradeCode::factory()->create();
        $this->be($user = User::factory(['role_id'=>1])->create());
        $response = $this->post('/upgrade', ['code'=> 'fake code']);

        $response->assertSessionHasErrors();
    }

    public function test_user_with_valid_key_has_role_upgraded()
    {
        $code = UpgradeCode::factory()->create();
        $this->be($user = User::factory(['role_id'=>1])->create());
        $response = $this->post('/upgrade', ['code'=> $code->upgrade_code]);

        $this->assertDatabaseHas('users', ['id'=> $user->id, 'role_id'=>2]);
    }

}
