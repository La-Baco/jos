<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use App\Services\JobMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobMatchingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_matching_service_calculates_correct_score_for_admin_assistant_demo(): void
    {
        $matchingService = new JobMatchingService;

        $user = User::where('email', 'user@jos.test')->first();
        $this->assertNotNull($user);

        // Find the Staff Administrasi job in Sumenep
        $job = Job::where('title', 'like', '%Administrasi Umum%')->first();
        $this->assertNotNull($job);

        $result = $matchingService->match($user, $job);

        // Assert that all breakdown components exist
        $this->assertArrayHasKey('total_score', $result);
        $this->assertArrayHasKey('skill_score', $result);
        $this->assertArrayHasKey('education_score', $result);
        $this->assertArrayHasKey('location_score', $result);
        $this->assertArrayHasKey('experience_score', $result);
        $this->assertArrayHasKey('interest_score', $result);
        $this->assertArrayHasKey('age_score', $result);

        // Budi has SMA and Job requires SMA -> Education score must be 100%
        $this->assertEquals(100, $result['education_score']);

        // Budi is in Kota Sumenep and Job is in Kota Sumenep -> Location score must be 100%
        $this->assertEquals(100, $result['location_score']);

        // Total score should be very high (>= 90%)
        $this->assertGreaterThanOrEqual(90, $result['total_score']);
    }

    public function test_education_hierarchy_matching(): void
    {
        $matchingService = new JobMatchingService;

        // User Siti has S1
        $siti = User::where('email', 'siti@jos.test')->first();
        $this->assertNotNull($siti);

        // Job requiring SMA
        $smaJob = Job::where('title', 'like', '%Administrasi Umum%')->first();
        $result = $matchingService->match($siti, $smaJob);

        // S1 satisfies SMA requirement -> 100%
        $this->assertEquals(100, $result['education_score']);
    }

    public function test_remote_job_scores_100_percent_location(): void
    {
        $matchingService = new JobMatchingService;

        $user = User::where('email', 'user@jos.test')->first();
        $remoteJob = Job::where('is_remote', true)->first();
        $this->assertNotNull($remoteJob);

        $result = $matchingService->match($user, $remoteJob);
        $this->assertEquals(100, $result['location_score']);
    }
}
