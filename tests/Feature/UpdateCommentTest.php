<?php

namespace Tests\Feature;

use App\Models\Bucket;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;
use Tonysm\TurboLaravel\Testing\AssertableTurboStream;
use Tonysm\TurboLaravel\Testing\InteractsWithTurbo;
use Tonysm\TurboLaravel\Testing\TurboStreamMatcher;

use function Tonysm\TurboLaravel\dom_id;

class UpdateCommentTest extends TestCase
{
    use InteractsWithTurbo;

    private User $user;
    private Bucket $bucket;
    private Recording $blog;
    private Recording $post;
    private Recording $comment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->withPersonalTeam()->create();
        $this->bucket = $this->user->currentTeam->bucket;
        $this->blog = $this->bucket->recordings()->blog()->firstOrFail();

        $this->post = Recording::factory()
            ->post()
            ->for($this->user, 'creator')
            ->for($this->blog, 'parent')
            ->for($this->bucket)
            ->create();

        $this->comment = Recording::factory()
            ->for($this->user, 'creator')
            ->for($this->post, 'parent')
            ->for($this->bucket)
            ->comment(['content' => '<p>Old content</p>'])
            ->create();
    }

    /** @test */
    public function only_authors_can_view_edit_comment_form()
    {
        $commentRecording = Recording::factory()
            ->for($this->bucket)
            ->for($this->post, 'parent')
            ->comment(['content' => '<p>Old content</p>'])
            ->create();

        $this->actingAs($this->user)
            ->get(route('buckets.comments.edit', [$this->bucket, $commentRecording]))
            ->assertForbidden();
    }

    /** @test */
    public function can_view_edit_comment_form()
    {
        $this->actingAs($this->user)
            ->get(route('buckets.comments.edit', [$this->bucket, $this->comment]))
            ->assertOk();
    }

    /** @test */
    public function only_authors_can_edit_their_comments()
    {
        $commentRecording = Recording::factory()
            ->for($this->bucket)
            ->for($this->post, 'parent')
            ->comment(['content' => '<p>Old content</p>'])
            ->create();

        $this->actingAs($this->user)
            ->put(route('buckets.comments.update', [$this->bucket, $commentRecording]))
            ->assertForbidden();

        $this->assertStringContainsString('<p>Old content</p>', (string) $commentRecording->refresh()->recordable->content);
    }

    /** @test */
    public function validates_comment_data()
    {
        $this->actingAs($this->user)
            ->put(route('buckets.comments.update', [$this->bucket, $this->comment]), [
                'content' => '',
            ])
            ->assertInvalid(['content']);

        $this->assertStringContainsString('<p>Old content</p>', (string) $this->comment->refresh()->recordable->content);
    }

    /** @test */
    public function update_comment()
    {
        $this->actingAs($this->user)
            ->put(route('buckets.comments.update', [$this->bucket, $this->comment]), [
                'content' => '<p>Updated content</p>',
            ])
            ->assertValid()
            ->assertRedirect(route('buckets.posts.show', [
                'bucket' => $this->bucket,
                'post' => $this->post,
                $this->comment->pageFragmentId(),
            ]));

        $this->assertStringContainsString('<p>Updated content</p>', (string) $this->comment->refresh()->recordable->content);
    }

    /** @test */
    public function update_comment_with_turbo()
    {
        $this->actingAs($this->user)
            ->turbo()
            ->put(route('buckets.comments.update', [$this->bucket, $this->comment]), [
                'content' => '<p>Updated content</p>',
            ])
            ->assertValid()
            ->assertTurboStream(fn (AssertableTurboStream $streams) => (
                $streams->has(1)
                    ->hasTurboStream(fn (TurboStreamMatcher $stream) => (
                        $stream
                            ->where('target', dom_id($this->comment))
                            ->where('action', 'replace')
                            ->see('Updated content')
                    ))
            ));

        $this->assertStringContainsString('<p>Updated content</p>', (string) $this->comment->refresh()->recordable->content);
    }
}
