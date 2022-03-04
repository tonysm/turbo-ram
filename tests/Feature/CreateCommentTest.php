<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
   /** @test */
   public function must_be_from_same_team_to_view_create_comment_form()
   {
        $user = User::factory()->withPersonalTeam()->create();
        $recording = Recording::factory()->post()->create();

        $this->actingAs($user)
            ->get(route('buckets.recordings.comments.create', [$recording->bucket, $recording]))
            ->assertForbidden();
   }

   /** @test */
   public function can_view_create_comment_form()
   {
        $user = User::factory()->withPersonalTeam()->create();
        $recording = Recording::factory()->post()->for($user->currentTeam->bucket)->create();

        $this->actingAs($user)
            ->get(route('buckets.recordings.comments.create', [$recording->bucket, $recording]))
            ->assertOk();
   }

    /** @test */
    public function must_be_from_same_team_as_bucket_to_comment_on_recording()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $recording = Recording::factory()->post()->create();

        $this->actingAs($user)
            ->post(route('buckets.recordings.comments.store', [$recording->bucket, $recording]), [
                'content' => '<p>So good!</p>',
            ])
            ->assertForbidden();
    }

    /** @test */
    public function validates_comment_data()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $recording = Recording::factory()->post()->for($user->currentTeam->bucket)->create();

        $this->actingAs($user)
            ->post(route('buckets.recordings.comments.store', [$recording->bucket, $recording]), [
                'content' => '',
            ])
            ->assertInvalid(['content']);
    }

    /** @test */
    public function can_create_comment_on_recording()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $recording = Recording::factory()->post()->for($user->currentTeam->bucket)->create();

        $response = $this->actingAs($user)
            ->post(route('buckets.recordings.comments.store', [$recording->bucket, $recording]), [
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
            'post' => $recording,
            $recording->children->first()->pageFragmentId(),
        ]));
    }
}
