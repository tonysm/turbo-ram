<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Tests\TestCase;

class UpdatePostsTest extends TestCase
{
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
        $post = Post::factory()->create();

        $recording = Recording::factory()->create([
            'bucket_id' => $user->currentTeam->bucket,
            'creator_id' => $user,
            'recordable_id' => $post->getKey(),
            'recordable_type' => $post->getMorphClass(),
        ]);

        $this->actingAs($user)
            ->put(route('buckets.posts.update', [$user->currentTeam->bucket, $recording]), value($payload))
            ->assertInvalid($expectedInvalidFields);
    }
}
