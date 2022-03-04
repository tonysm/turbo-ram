<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Bucket;
use App\Models\Dock;
use App\Models\Recording;
use App\Models\Team;
use Tests\TestCase;

class BucketTest extends TestCase
{
    /** @test */
    public function blog_is_automatically_created_and_docked_when_bucket_is_created()
    {
        $bucket = Bucket::withoutAutoCreation(fn () => (
            Bucket::factory()
                ->for(Team::factory([
                    'name' => 'Team Name',
                ]), 'bucketable')
                ->create()
        ));

        // When creating a Bucket, we will ensure to create its
        // first Dock, which will point to the Blog. Both of
        // these are recordings inside the Bucket itself.
        $this->assertCount(2, $bucket->recordings);
        $this->assertCount(1, $bucket->dock);
        $this->assertInstanceOf(Recording::class, $bucket->dock->first());
        $this->assertInstanceOf(Dock::class, $bucket->dock->first()->recordable);

        // The dock has a blog, which will act as a parent for all posts...
        $this->assertCount(1, $bucket->dock->first()->children);
        $this->assertInstanceOf(Blog::class, $bucket->dock->first()->children->first()->recordable);

        // Blog name...
        $this->assertEquals('Blog', $bucket->dock->first()->children->first()->recordable->name);
        // The slug uses the name of the team and appends a random string hash to it...
        $this->assertMatchesRegularExpression('/^team-name-\w{6}$/', $bucket->dock->first()->children->first()->recordable->slug);
    }
}
