<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    /** @test */
    public function must_own_the_post_to_delete_it()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $recording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->post([
                'title' => 'Old title',
                'content' => '<p>Old Content</p>',
            ])
            ->create();

        $this->actingAs($user)
            ->delete(route('buckets.posts.destroy', [$user->currentTeam->bucket, $recording]))
            ->assertForbidden();

        $this->assertModelExists($recording);
    }

    /** @test */
    public function can_delete_posts()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $recording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->post([
                'title' => 'Old title',
                'content' => '<p>Old Content</p>',
            ])
            ->create();

        $this->actingAs($user)
            ->delete(route('buckets.posts.destroy', [$user->currentTeam->bucket, $recording]))
            ->assertRedirect(route('dashboard'));

        $this->assertModelMissing($recording);
    }
}
