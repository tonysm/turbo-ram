<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class UpdatePostsTest extends TestCase
{
    /** @test */
    public function only_authors_can_view_the_form_to_edit_their_posts()
    {
        $user = User::factory()->withPersonalTeam()->create();

        // Recording from another user in the bucket/team...
        $recording = Recording::factory()->for($user->currentTeam->bucket)->create();

        $this->actingAs($user)
            ->get(route('buckets.posts.edit', [$user->currentTeam->bucket, $recording]))
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
        $user = User::factory()->withPersonalTeam()->create();

        $recording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->post()
            ->create();

        $this->actingAs($user)
            ->put(route('buckets.posts.update', [$user->currentTeam->bucket, $recording]), value($payload))
            ->assertInvalid($expectedInvalidFields);
    }

    /** @test */
    public function updates_post()
    {
        $user = User::factory()->withPersonalTeam()->create();

        $recording = Recording::factory()
            ->for($user->currentTeam->bucket)
            ->for($user, 'creator')
            ->post([
                'title' => 'Old title',
                'content' => '<p>Old Content</p>',
            ])
            ->create();

        $this->actingAs($user)
            ->put(route('buckets.posts.update', [$user->currentTeam->bucket, $recording]), [
                'title' => 'Updated title',
                'content' => '<p>Updated Content</p>',
            ])
            ->assertValid()
            ->assertRedirect(route('buckets.posts.show', [$user->refresh()->currentTeam->bucket, $recording]));

        $this->assertEquals('Updated title', $recording->refresh()->recordable->title);
        $this->assertStringContainsString('<p>Updated Content</p>', (string) $recording->recordable->content);
    }

    /** @test */
    public function only_authors_can_update_their_posts()
    {
        $user = User::factory()->withPersonalTeam()->create();

        // Recording from another user in the bucket/team...
        $recording = Recording::factory()->for($user->currentTeam->bucket)->create();

        $this->actingAs($user)
            ->put(route('buckets.posts.update', [$user->currentTeam->bucket, $recording]), [
                'title' => 'Updated title',
                'content' => '<p>Updated Content</p>',
            ])
            ->assertForbidden();
    }
}
