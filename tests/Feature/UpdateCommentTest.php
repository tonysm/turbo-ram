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

        $postRecording = Recording::factory()
            ->post()
            ->for($user, 'creator')
            ->for($user->currentTeam->bucket)
            ->create();

        $commentRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($postRecording, 'parent')
            ->comment(['content' => '<p>Old content</p>'])
            ->create();

        $this->actingAs($user)
            ->get(route('comments.edit', $commentRecording))
            ->assertForbidden();
    }

    /** @test */
    public function can_view_edit_comment_form()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $postRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->comment();

        $commentRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->for($postRecording, 'parent')
            ->comment(['content' => '<p>Old content</p>'])
            ->create();

        $this->actingAs($user)
            ->get(route('comments.edit', $commentRecording))
            ->assertOk();
    }

    /** @test */
    public function only_authors_can_edit_their_comments()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $postRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->post()
            ->create();

        $commentRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($postRecording, 'parent')
            ->comment(['content' => '<p>Old content</p>'])
            ->create();

        $this->actingAs($user)
            ->put(route('comments.update', $commentRecording))
            ->assertForbidden();

        $this->assertStringContainsString('<p>Old content</p>', (string) $commentRecording->refresh()->recordable->content);
    }

    /** @test */
    public function validates_comment_data()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $postRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->post()
            ->create();

        $commentRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->for($postRecording, 'parent')
            ->comment(['content' => '<p>Old content</p>'])
            ->create();

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

        $postRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->post()
            ->create();

        $commentRecording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->for($postRecording, 'parent')
            ->comment(['content' => '<p>Old content</p>'])
            ->create();

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
