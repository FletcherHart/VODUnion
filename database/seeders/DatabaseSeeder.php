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
        \App\Models\Video::factory(10)->create();
        \App\Models\Comment::factory(10)->create();
        
        
    }
}
