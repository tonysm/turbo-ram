<?php

namespace Tests\Feature;

use App\Models\Bucket;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListPostsTest extends TestCase
{
    /** @test */
    public function must_be_from_same_team_as_bucket_to_list_posts()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $bucket = Bucket::factory()->create();

        $this->actingAs($user)
            ->get(route('buckets.posts.index', [$bucket]))
            ->assertForbidden();
    }

    /** @test */
    public function can_list_posts()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $posts = Post::factory()->times(2)->create();

        $postRecordings = $posts->map(fn ($post) => (
            Recording::factory()
                ->for($post, 'recordable')
                ->for($user, 'creator')
                ->for($user->currentTeam->bucket, 'bucket')
                ->create()
        ));

        // Comments on the same bucket recordings for noise...
        $postRecordings->each(fn ($recording) => (
            Recording::factory()
                ->for(Comment::factory(), 'recordable')
                ->for($recording->bucket, 'bucket')
                ->for($user, 'creator')
                ->for($recording, 'parentRecording')
                ->create()
        ));

        // Recordings from other buckets for noise...
        Post::factory()->times(2)->create()->each(fn ($post) => (
            Recording::factory()
                ->for($post, 'recordable')
                ->create()
        ));

        $this->actingAs($user)
            ->get(route('buckets.posts.index', $user->currentTeam->bucket))
            ->assertOk()
            ->assertViewHas('posts', function ($posts) use ($postRecordings) {
                $this->assertCount(2, $posts);

                $postRecordings->each(fn ($post) => (
                    $this->assertTrue($posts->contains($post))
                ));

                return true;
            });
    }
}
