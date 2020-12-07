<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UploadVideoTest extends TestCase
{

    public function test_authenticated_user_can_upload_video()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
