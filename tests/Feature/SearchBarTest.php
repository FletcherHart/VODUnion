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

class SearchBarTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations, WithFaker;

    public function setUp():void {
        parent::setUp();
        $this->seed();
    }

    public function test_that_search_term_returns_all_videos_with_term_in_title()
    {
        $term = "bob";

        $videos = Video::factory(['title' => $this->faker->sentence . " bob"])->count(3)->create();

        $response = $this->get('/search/'.$term);

        $response->assertSee($videos[0]->title);
        $response->assertSee($videos[1]->title);
        $response->assertSee($videos[2]->title);
    }

    public function test_that_empty_search_redirects_to_front_page() {
        $response = $this->get('/search/');

        $response->assertRedirect('');
    }

}
