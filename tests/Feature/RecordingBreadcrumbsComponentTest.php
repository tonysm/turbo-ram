<?php

namespace Tests\Feature;

use App\Models\Bucket;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Recording;
use App\Models\Team;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Tests\TestCase;

class RecordingBreadcrumbsComponentTest extends TestCase
{
    use InteractsWithViews;

    /** @test */
    public function renders_recording_parent_tree()
    {
        $team = Team::factory()->create([
            'name' => 'Team Name',
        ]);

        $post = Post::factory()->create([
            'title' => 'Post Title',
        ]);

        $postRecording = Recording::factory()->create([
            'bucket_id' => $team->bucket,
            'recordable_type' => $post->getMorphClass(),
            'recordable_id' => $post->getKey(),
        ]);

        $comment = Comment::factory()->create();

        $commentRecording = Recording::factory()->create([
            'bucket_id' => $team->bucket,
            'parent_id' => $postRecording,
            'recordable_type' => $comment->getMorphClass(),
            'recordable_id' => $comment->getKey(),
        ]);

        $this->blade('<x-recording-breadcrumbs :recording="$recording" />', ['recording' => $commentRecording])
            ->assertSee('Post Title');
    }
}
