<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
   /** @test */
   public function must_be_from_same_team_to_view_create_comment_form()
   {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Post::factory()->create();

        $recording = Recording::factory()->create([
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $this->actingAs($user)
            ->get(route('recordings.comments.create', [$recording]))
            ->assertForbidden();
   }

   /** @test */
   public function can_view_create_comment_form()
   {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Post::factory()->create();

        $recording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $this->actingAs($user)
            ->get(route('recordings.comments.create', [$recording]))
            ->assertOk();
   }

    /** @test */
    public function must_be_from_same_team_as_bucket_to_comment_on_recording()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Post::factory()->create();

        $recording = Recording::factory()->create([
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $this->actingAs($user)
            ->post(route('recordings.comments.store', [$recording]), [
                'content' => '<p>So good!</p>',
            ])
            ->assertForbidden();
    }

    /** @test */
    public function validates_comment_data()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Post::factory()->create();

        $recording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $this->actingAs($user)
            ->post(route('recordings.comments.store', [$recording]), [
                'content' => '',
            ])
            ->assertInvalid(['content']);
    }

    /** @test */
    public function can_create_comment_on_recording()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Post::factory()->create();

        $recording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $response = $this->actingAs($user)
            ->post(route('recordings.comments.store', [$recording]), [
                'content' => '<p>So good!</p>',
            ])
            ->assertRedirect();

        $recording->refresh();

        $this->assertCount(1, $recording->children);
        $this->assertInstanceOf(Recording::class, $recording->children->first());
        $this->assertTrue($recording->children->first()->creator->is($user));
        $this->assertInstanceOf(Comment::class, $recording->children->first()->recordable);
        $this->assertStringContainsString('<p>So good!</p>', (string) $recording->children->first()->recordable->content);

        $response->assertRedirect(route('buckets.posts.show', [
            'bucket' => $recording->bucket,
            'recording' => $recording,
            $recording->children->first()->pageFragmentId(),
        ]));
    }
}
