<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Video;

class VideoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;

    public function test_user_can_browse_videos()
    {
        $video = Video::factory()->create();
        $response = $this->get('/');

        $response->assertSee($video->title);
    }
}
