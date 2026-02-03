<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'KoKo',
            'email' => 'koko@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'MgMg',
            'email' => 'mgmg@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'ZawZaw',
            'email' => 'zawzaw@gmail.com',
        ]);

        //Role
        //AdminRole
        Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin']
        );

        //User Role
        Role::updateOrCreate(
            ['slug' => 'user'],
            ['name' => 'User']
        );

        //Post
        Post::factory(20)->create();

        //Comment
        Comment::factory(50)->create();
    }
}
