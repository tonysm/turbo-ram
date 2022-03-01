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
        $post = Post::factory()->create([
            'title' => 'Hello Post',
            'content' => '<p>Content stuff!</p>',
        ]);

        $recording = Recording::factory()->create([
            'recordable_id' => $post->getKey(),
            'recordable_type' => $post->getMorphClass(),
        ]);

        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user)
            ->get(route('buckets.posts.show', [$user->currentTeam->bucket, $recording]))
            ->assertForbidden();
    }

    /** @test */
    public function can_view_post()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Post::factory()->create([
            'title' => 'Hello Post',
            'content' => '<p>Content stuff!</p>',
        ]);

        $recording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'recordable_id' => $post->getKey(),
            'recordable_type' => $post->getMorphClass(),
        ]);

        $this->actingAs($user)
            ->get(route('buckets.posts.show', [$user->currentTeam->bucket, $recording]))
            ->assertOk()
            ->assertSee('Hello Post')
            ->assertSee('Content stuff!');
    }
}
