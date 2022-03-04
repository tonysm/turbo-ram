<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class ViewPostTest extends TestCase
{
    /** @test */
    public function must_be_from_same_team_as_bucket_to_view_post()
    {
        $recording = Recording::factory()->post([
            'title' => 'Hello Post',
            'content' => '<p>Content stuff!</p>',
        ])->create();

        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user)
            ->get(route('buckets.posts.show', [$user->currentTeam->bucket, $recording]))
            ->assertForbidden();
    }

    /** @test */
    public function can_view_post()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $recording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->post([
                'title' => 'Hello Post',
                'content' => '<p>Content stuff!</p>',
            ])
            ->create();

        $this->actingAs($user)
            ->get(route('buckets.posts.show', [$user->currentTeam->bucket, $recording]))
            ->assertOk()
            ->assertSee('Hello Post')
            ->assertSee('Content stuff!');
    }
}
