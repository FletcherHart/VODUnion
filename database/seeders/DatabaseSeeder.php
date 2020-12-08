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
}
