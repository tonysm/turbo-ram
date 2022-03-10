<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Recording;
use Tests\TestCase;

class EventableTest extends TestCase
{
    /** @test */
    public function creating_posts_generate_events_on_the_bucket()
    {
        $postRecording = Recording::factory()->post()->create();

        $this->assertCount(1, $postRecording->refresh()->bucket->events);
        $this->assertTrue($postRecording->is($postRecording->bucket->events->first()->recording));
        $this->assertTrue($postRecording->recordable->is($postRecording->bucket->events->first()->recordable));
        $this->assertTrue($postRecording->creator->is($postRecording->bucket->events->first()->creator));
        $this->assertEquals('post_created', $postRecording->bucket->events->first()->event_type);
    }

    /** @test */
    public function updating_posts_generate_events_on_the_bucket()
    {
        $postRecording = Recording::factory()->post()->create();

        $this->actingAs($postRecording->creator);

        $postRecording->update([
            'recordable' => $recordable = Post::factory()->create(),
        ]);

        $this->assertCount(2, $postRecording->refresh()->bucket->events);

        $updatePostEvent = $postRecording->bucket->events->firstWhere('event_type', 'post_updated');

        $this->assertNotNull($updatePostEvent, 'Missing an event with event_type equals to "post_updated".');
        $this->assertTrue($postRecording->is($updatePostEvent->recording));
        $this->assertTrue($recordable->is($updatePostEvent->recordable));
        $this->assertTrue($postRecording->creator->is($updatePostEvent->creator));
    }
}
