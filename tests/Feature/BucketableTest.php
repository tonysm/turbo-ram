<?php

namespace Tests\Feature;

use App\Models\Bucket;
use App\Models\Team;
use Tests\TestCase;

class BucketableTest extends TestCase
{
    /**
     * @test
     * @dataProvider bucketablesData
     */
    public function bucket_is_automatically_created($bucketableFactory)
    {
        $bucketable = $bucketableFactory();

        $this->assertNotNull($bucketable->bucket);
        $this->assertInstanceOf(Bucket::class, $bucketable->bucket);
    }

    public function bucketablesData()
    {
        return [
            'teams' => [fn () => Team::factory()->create()],
        ];
    }
}
