<?php

namespace Tests\Feature;

use App\Models\Bucket;
use App\Models\Post;
use App\Models\Recording;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class CreatePostsTest extends TestCase
{
    private User $user;
    private Bucket $bucket;
    private Recording $blog;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->withPersonalTeam()->create();
        $this->bucket = $this->user->currentTeam->bucket;
        $this->blog = $this->user->currentTeam->bucket->recordings()->blog()->first();
    }

    /** @test */
    public function must_be_from_same_team_to_view_create_posts_form()
    {
        $anotherTeam = Team::factory()->create();

        $blog = $anotherTeam->bucket->dock->first();

        $this->actingAs(User::factory()->withPersonalTeam()->create())
            ->get(route('buckets.blogs.posts.create', [$anotherTeam->bucket, $blog]))
            ->assertForbidden();
    }

    /** @test */
    public function view_create_posts_form()
    {
        $this->actingAs($this->user)
            ->get(route('buckets.blogs.posts.create', [$this->bucket, $this->blog]))
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
        $this->actingAs($this->user)
            ->post(route('buckets.blogs.posts.store', [$this->bucket, $this->blog]), value($payload))
            ->assertInvalid($expectedInvalidFields);
    }

    /** @test */
    public function creates_post()
    {
        $this->actingAs($this->user)
            ->post(route('buckets.blogs.posts.store', [$this->bucket, $this->blog]), [
                'title' => 'Some post',
                'content' => '<p>Hello World</p>',
            ])
            ->assertValid()
            ->assertRedirect();

        $this->assertCount(3, $this->bucket->refresh()->recordings);
        $this->assertCount(1, $this->blog->refresh()->children);
        $this->assertInstanceOf(Post::class, $this->blog->children->first()->recordable);
        $this->assertEquals('Some post', $this->blog->children->first()->recordable->title);
        $this->assertStringContainsString('<p>Hello World</p>', (string) $this->blog->children->first()->recordable->content);
    }

    /** @test */
    public function must_be_from_same_team_as_bucket_to_create_post()
    {
        $anotherTeam = Team::factory()->create();

        $this->actingAs($this->user)
            ->post(route('buckets.blogs.posts.store', [$anotherTeam->bucket, $blog = $anotherTeam->bucket->recordings()->blog()->firstOrFail()]), [
                'title' => 'Some post',
                'content' => '<p>Hello World</p>',
            ])
            ->assertForbidden();

        $this->assertCount(0, $blog->refresh()->children);
        $this->assertCount(2, $anotherTeam->refresh()->bucket->recordings);
    }
}
