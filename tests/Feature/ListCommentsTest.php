<?php

namespace Tests\Feature;

use App\Models\Bucket;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class ListCommentsTest extends TestCase
{
    private User $user;
    private Bucket $bucket;
    private Recording $blog;
    private Recording $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->withPersonalTeam()->create();
        $this->bucket = $this->user->currentTeam->bucket;
        $this->blog = $this->bucket->recordings()->blog()->firstOrFail();

        $this->post = Recording::factory()
            ->for($this->bucket)
            ->for($this->user, 'creator')
            ->for($this->blog, 'parent')
            ->post()
            ->create();
    }

    /** @test */
    public function must_be_from_same_team_as_recording_to_see_comments()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user)
            ->get(route('buckets.recordings.comments.index', [$this->bucket, $this->post]))
            ->assertForbidden();
    }

    /** @test */
    public function list_comments()
    {
        $commentsRecordings = Recording::factory()
            ->times(3)
            ->for($this->bucket)
            ->for($this->post, 'parent')
            ->comment()
            ->create();

        $this->actingAs($this->user)
            ->get(route('buckets.recordings.comments.index', [$this->bucket, $this->post]))
            ->assertOk()
            ->assertViewHas('recording', $this->post)
            ->assertViewHas('comments', function ($comments) use ($commentsRecordings) {
                $this->assertCount(3, $comments);

                $comments->each(fn ($comment) => (
                    $this->assertTrue($commentsRecordings->contains($comment))
                ));

                return true;
            });
    }
}
