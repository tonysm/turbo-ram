<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class UpdateCommentTest extends TestCase
{
    /** @test */
    public function only_authors_can_view_edit_comment_form()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Comment::factory()->create();

        $postRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $comment = Comment::factory()->create([
            'content' => '<p>Old content</p>',
        ]);

        $commentRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'parent_id' => $postRecording,
            'recordable_type' => $comment->getMorphClass(),
            'recordable_id' => $comment->getKey(),
        ]);

        $this->actingAs($user)
            ->get(route('comments.edit', $commentRecording))
            ->assertForbidden();
    }

    /** @test */
    public function can_view_edit_comment_form()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Comment::factory()->create();

        $postRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $comment = Comment::factory()->create([
            'content' => '<p>Old content</p>',
        ]);

        $commentRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'parent_id' => $postRecording,
            'recordable_type' => $comment->getMorphClass(),
            'recordable_id' => $comment->getKey(),
        ]);

        $this->actingAs($user)
            ->get(route('comments.edit', $commentRecording))
            ->assertOk();
    }

    /** @test */
    public function only_authors_can_edit_their_comments()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Comment::factory()->create();

        $postRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $comment = Comment::factory()->create([
            'content' => '<p>Old content</p>',
        ]);

        $commentRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'parent_id' => $postRecording,
            'recordable_type' => $comment->getMorphClass(),
            'recordable_id' => $comment->getKey(),
        ]);

        $this->actingAs($user)
            ->put(route('comments.update', $commentRecording))
            ->assertForbidden();

        $this->assertStringContainsString('<p>Old content</p>', (string) $commentRecording->refresh()->recordable->content);
    }

    /** @test */
    public function validates_comment_data()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Comment::factory()->create();

        $postRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $comment = Comment::factory()->create([
            'content' => '<p>Old content</p>',
        ]);

        $commentRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'parent_id' => $postRecording,
            'recordable_type' => $comment->getMorphClass(),
            'recordable_id' => $comment->getKey(),
        ]);

        $this->actingAs($user)
            ->put(route('comments.update', $commentRecording), [
                'content' => '',
            ])
            ->assertInvalid(['content']);

        $this->assertStringContainsString('<p>Old content</p>', (string) $commentRecording->refresh()->recordable->content);
    }

    /** @test */
    public function update_comment()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Comment::factory()->create();

        $postRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $comment = Comment::factory()->create([
            'content' => '<p>Old content</p>',
        ]);

        $commentRecording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'parent_id' => $postRecording,
            'recordable_type' => $comment->getMorphClass(),
            'recordable_id' => $comment->getKey(),
        ]);

        $this->actingAs($user)
            ->put(route('comments.update', $commentRecording), [
                'content' => '<p>Updated content</p>',
            ])
            ->assertValid()
            ->assertRedirect(route('buckets.posts.show', [
                'bucket' => $user->currentTeam->bucket,
                'recording' => $postRecording,
                $commentRecording->pageFragmentId(),
            ]));

        $this->assertStringContainsString('<p>Updated content</p>', (string) $commentRecording->refresh()->recordable->content);
    }
}
