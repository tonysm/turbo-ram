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

        Recording::factory()
            ->times(20)
            ->for($user->currentTeam->bucket, 'bucket')
            ->for($user, 'creator')
            ->for($user->currentTeam->bucket->recordings()->blog()->firstOrFail(), 'parent')
            ->post()
            ->create();
    }
}
