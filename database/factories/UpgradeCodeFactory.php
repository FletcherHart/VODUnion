<?php

namespace Database\Factories;

use App\Models\UpgradeCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UpgradeCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UpgradeCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'upgrade_code' => hash('md5', Str::random(10)),
        ];
    }
}
