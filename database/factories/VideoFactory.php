<?php

namespace Database\Factories;

use App\Models\Video;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::where('role_id', '>=', 2)
            ->inRandomOrder()
            ->first('id')
            ->toArray();
        return [
            'user_id' => $user['id'],
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'views' => $this->faker->randomNumber,
            'uploadUrl' => 'river.mp4',
            'videoID' => $this->faker->numberBetween(0,100000),
            'listed' => false,
            'sizeKB' => $this->faker->numberBetween(10, 100000),
            'status' => 'done',
            'video_length' => 10
        ];
    }
}
