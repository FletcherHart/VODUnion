<?php

namespace Database\Factories;

use App\Models\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::whereNotIn('id',function ($query) {
                $query->select('user_id')->from('user_roles');
            })->first('id')
            ->toArray();
        return [
            'user_id' => $user['id'],
            'role_id' => Role::inRandomOrder()->first()
        ];
    }
}
