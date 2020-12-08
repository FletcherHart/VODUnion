<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UploadVideoTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    public function test_authenticated_user_can_upload_video()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
