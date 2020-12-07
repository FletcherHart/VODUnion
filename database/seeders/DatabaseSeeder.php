<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class
        ]);
        \App\Models\User::factory(10)->create();
        //Reason for For Loop below:
        //Factory calls create on all data at once, causing x number of the same record
        for ($x = 0; $x < 10; $x++) {
            \App\Models\UserRole::factory()->create();
        }
        \App\Models\Video::factory(10)->create();
        \App\Models\Comment::factory(10)->create();
        
        
    }

    private function seedRoles($users) {
        DB::table('user_roles')->insert(
            [
                'user_id' => \App\Models\User::select('id')->orderByRaw("RAND()")->first()->id,
                'role_id' => \App\Models\Role::select('id')->orderByRaw("RAND()")->first()->id,
            ]
        );
    }
}
