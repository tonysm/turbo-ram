<?php

namespace Database\Factories;

use App\Models\Bucket;
use App\Models\Post;
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
}
