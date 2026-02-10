<?php

namespace Tests\Feature;

use App\Services\DuplicateCleanupService;
use Tests\TestCase;

class DuplicateCleanupTest extends TestCase
{
    public function test_cleanup_route_runs_successfully()
    {
        $this->mock(DuplicateCleanupService::class)
            ->shouldReceive('cleanup')
            ->once();

        $response = $this->get('/cleanup-duplicates');
        $response->assertStatus(200);
    }
}
