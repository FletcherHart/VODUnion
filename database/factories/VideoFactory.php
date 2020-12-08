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
            'storedAt' => 'river.mp4',
            'listed' => true
        ];
    }
}
