<?php

namespace Tests\Feature;

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
            ->for(Recording::factory()->blog()->for($user->currentTeam->bucket), 'parent')
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
            ->for(Recording::factory()->blog()->for($user->currentTeam->bucket), 'parent')
            ->post([
                'title' => 'Old title',
                'content' => '<p>Old Content</p>',
            ])
            ->create();

        $this->actingAs($user)
            ->delete(route('buckets.posts.destroy', [$user->currentTeam->bucket, $recording]))
            ->assertRedirect(route('buckets.blogs.show', [$user->currentTeam->bucket, $recording->parent]));

        $this->assertModelMissing($recording);
    }
}
