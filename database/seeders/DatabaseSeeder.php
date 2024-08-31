<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => '',
            'password' => 'admin',
        ]);
        if ($admin) {
            print $admin->createToken("api-token")->plainTextToken;
        }
    }
}
