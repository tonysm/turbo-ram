<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class CreatePostsTest extends TestCase
{
    /** @test */
    public function must_be_from_same_team_to_view_create_posts_form()
    {
        $anotherTeam = Team::factory()->create();

        $this->actingAs(User::factory()->withPersonalTeam()->create())
            ->get(route('buckets.posts.create', $anotherTeam->bucket))
            ->assertForbidden();
    }

    /** @test */
    public function view_create_posts_form()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create())
            ->get(route('buckets.posts.create', $user->currentTeam->bucket))
            ->assertOk();
    }

    public function invalidData()
    {
        return [
            'required fields' => [
                'payload' => ['title' => '', 'content' => ''],
                'expectedInvalidFields' => ['title', 'content'],
            ],
            'title field has a max limit' => [
                'payload' => ['title' => str()->random(256), 'content' => 'hello'],
                'expectedInvalidFields' => ['title'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider invalidData
     */
    public function validates_posts_payload($payload, $expectedInvalidFields)
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create())
            ->post(route('buckets.posts.store', $user->currentTeam->bucket), value($payload))
            ->assertInvalid($expectedInvalidFields);
    }

    /** @test */
    public function creates_post()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create())
            ->post(route('buckets.posts.store', $user->currentTeam->bucket), [
                'title' => 'Some post',
                'content' => '<p>Hello World</p>',
            ])
            ->assertValid()
            ->assertRedirect();

        $this->assertCount(1, $user->refresh()->currentTeam->bucket->recordings);
        $this->assertInstanceOf(Post::class, $user->currentTeam->bucket->recordings->first()->recordable);
        $this->assertEquals('Some post', $user->currentTeam->bucket->recordings->first()->recordable->title);
        $this->assertStringContainsString('<p>Hello World</p>', (string) $user->currentTeam->bucket->recordings->first()->recordable->content);
    }

    /** @test */
    public function must_be_from_same_team_as_bucket_to_create_post()
    {
        $anotherTeam = Team::factory()->create();

        $this->actingAs($user = User::factory()->withPersonalTeam()->create())
            ->post(route('buckets.posts.store', $anotherTeam->bucket), [
                'title' => 'Some post',
                'content' => '<p>Hello World</p>',
            ])
            ->assertForbidden();

        $this->assertCount(0, $user->refresh()->currentTeam->bucket->recordings);
        $this->assertCount(0, $anotherTeam->refresh()->bucket->recordings);
    }
}
