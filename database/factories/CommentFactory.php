<?php

namespace Database\Factories;

use App\Models\Video;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $video = Video::inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'video_id' => $video->id,
            'text' => $this->faker->paragraph,
        ];
    }
}
