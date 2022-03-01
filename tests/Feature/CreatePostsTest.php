<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class CreatePostsTest extends TestCase
{
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

    /** @test */
    public function creates_post()
    {
    }
}
