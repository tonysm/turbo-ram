<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->withPersonalTeam()->create([
            'name' => 'Tony Messias',
            'email' => 'tonysm@hey.com',
        ]);

        Post::factory()->times(20)->create()->each(fn ($post) => Recording::factory()
            ->for($user->currentTeam->bucket, 'bucket')
            ->for($user, 'creator')
            ->for($post, 'recordable')
            ->create());
    }
}
