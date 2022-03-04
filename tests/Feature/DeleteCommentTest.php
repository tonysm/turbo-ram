<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    /** @test */
    public function only_author_can_delete_their_comments()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $recording = Recording::factory()->comment()->create();

        $this->actingAs($user)
            ->delete(route('comments.destroy', $recording))
            ->assertForbidden();

        $this->assertModelExists($recording);
    }

    /** @test */
    public function delete_comment()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $postRecording = Recording::factory()
            ->post()
            ->for($user->currentTeam->bucket, 'bucket')
            ->for($user, 'creator')
            ->create();

        $commentRecording = Recording::factory()
            ->comment()
            ->for($user->currentTeam->bucket, 'bucket')
            ->for($user, 'creator')
            ->for($postRecording, 'parent')
            ->create();

        $this->actingAs($user)
            ->delete(route('comments.destroy', $commentRecording))
            ->assertRedirect(route('buckets.posts.show', [
                'bucket' => $user->currentTeam->bucket,
                'recording' => $postRecording,
            ]));

        $this->assertModelMissing($commentRecording);
    }
}
