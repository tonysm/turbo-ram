<?php

namespace Tests\Feature;

use App\Models\Bucket;
use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdatePostsTest extends TestCase
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
            ->post([
                'title' => 'Old Title',
                'content' => '<p>Old content</p>',
            ])
            ->create();
    }

    /** @test */
    public function only_authors_can_view_the_form_to_edit_their_posts()
    {
        // Recording from another user in the bucket...
        $post = Recording::factory()->for($this->bucket)->post()->create();

        $this->actingAs($this->user)
            ->get(route('buckets.posts.edit', [$this->bucket, $post]))
            ->assertForbidden();
    }

    /** @test */
    public function can_view_edit_posts_form()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $recording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->post()
            ->create();

        $this->actingAs($user)
            ->get(route('buckets.posts.edit', [$user->currentTeam->bucket, $recording]))
            ->assertOk();
    }

    public function invalidData()
    {
        return [
            'required fields' => [
                'payload' => ['title' => '', 'content' => ''],
                'invalid' => ['title', 'content'],
            ],
            'title has a limit' => [
                'payload' => ['title' => str()->random(256), 'content' => 'hello'],
                'invalid' => ['title'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider invalidData
     */
    public function validates_post_payload($payload, $expectedInvalidFields)
    {
        $this->actingAs($this->user)
            ->put(route('buckets.posts.update', [$this->bucket, $this->post]), value($payload))
            ->assertInvalid($expectedInvalidFields);
    }

    /** @test */
    public function updates_post()
    {
        $this->actingAs($this->user)
            ->put(route('buckets.posts.update', [$this->bucket, $this->post]), [
                'title' => 'Updated title',
                'content' => '<p>Updated Content</p>',
            ])
            ->assertValid()
            ->assertRedirect(route('buckets.posts.show', [$this->bucket, $this->post]));

        $this->assertEquals('Updated title', $this->post->refresh()->recordable->title);
        $this->assertStringContainsString('<p>Updated Content</p>', (string) $this->post->recordable->content);
    }

    /** @test */
    public function only_authors_can_update_their_posts()
    {
        // Recording from another user in the bucket/team...
        $post = Recording::factory()->for($this->bucket)->create();

        $this->actingAs($this->user)
            ->put(route('buckets.posts.update', [$this->bucket, $post]), [
                'title' => 'Updated title',
                'content' => '<p>Updated Content</p>',
            ])
            ->assertForbidden();
    }
}
