<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Bucket;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Recording;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recording>
 */
class RecordingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bucket_id' => Bucket::factory(),
            'creator_id' => User::factory(),
            'status' => 'active',
            'recordable_type' => (new Post())->getMorphClass(),
            'recordable_id' => Post::factory(),
        ];
    }

    public function blog(array $overrides = [])
    {
        return $this->state([
            'recordable_type' => (new Blog())->getMorphClass(),
            'recordable_id' => Blog::factory($overrides),
        ]);
    }

    public function post(array $overrides = [])
    {
        return $this->state([
            'recordable_type' => (new Post())->getMorphClass(),
            'recordable_id' => Post::factory($overrides),
        ]);
    }

    public function comment(array $overrides = [])
    {
        return $this->state([
            'recordable_type' => (new Comment())->getMorphClass(),
            'recordable_id' => Comment::factory($overrides),
        ]);
    }
}
