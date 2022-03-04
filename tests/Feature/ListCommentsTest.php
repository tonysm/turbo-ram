<?php

namespace Tests\Feature;

use App\Models\Bucket;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class ListCommentsTest extends TestCase
{
    /** @test */
    public function must_be_from_same_team_as_recording_to_see_comments()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $bucket = Bucket::factory()->create();
        $post = Post::factory()->create();
        $recording = Recording::factory()->for($bucket)->for($post, 'recordable')->create();

        $this->actingAs($user)
            ->get(route('recordings.comments.index', $recording))
            ->assertForbidden();
    }

    /** @test */
    public function list_comments()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $post = Post::factory()->create();

        $recording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($post, 'recordable')
            ->create();

        $comments = Comment::factory()->times(3)->create();

        $commentsRecordings = $comments->map(fn ($comment) => (
            Recording::factory()
                ->for($user->currentTeam->bucket)
                ->for($comment, 'recordable')
                ->for($recording, 'parent')
                ->create()
        ));

        $this->actingAs($user)
            ->get(route('recordings.comments.index', $recording))
            ->assertOk()
            ->assertViewHas('recording', $recording)
            ->assertViewHas('comments', function ($comments) use ($commentsRecordings) {
                $this->assertCount(3, $comments);

                $comments->each(fn ($comment) => (
                    $this->assertTrue($commentsRecordings->contains($comment))
                ));

                return true;
            });
    }
}
