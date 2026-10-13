<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_register_and_is_redirected_to_profile(): void
    {
        $response = $this->post('/register', [
            'name' => 'Faris Sumenep',
            'email' => 'faris@example.com',
            'phone' => '081234567899',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('user.profile.index'));
        $this->assertAuthenticated();

        $user = User::where('email', 'faris@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('job_seeker', $user->role);
        $this->assertNotNull($user->profile);
        $this->assertEquals('Sumenep', $user->profile->regency);
    }

    public function test_admin_is_redirected_to_admin_dashboard_upon_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@jos.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_job_seeker_cannot_access_admin_dashboard(): void
    {
        $user = User::where('email', 'user@jos.test')->first();

        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_user_can_view_job_detail_and_toggle_saved(): void
    {
        $user = User::where('email', 'user@jos.test')->first();
        $job = Job::first();

        // View job detail
        $response = $this->actingAs($user)->get(route('jobs.show', $job->id));
        $response->assertStatus(200);
        $response->assertSeeText($job->title);
        $response->assertSeeText('Mengapa lowongan ini cocok untuk Anda?');

        // Save job
        $saveResponse = $this->actingAs($user)->post(route('jobs.save', $job->id));
        $saveResponse->assertSessionHas('success');
        $this->assertTrue($user->savedJobs()->where('job_id', $job->id)->exists());

        // Unsave job
        $unsaveResponse = $this->actingAs($user)->post(route('jobs.save', $job->id));
        $unsaveResponse->assertSessionHas('success');
        $this->assertFalse($user->savedJobs()->where('job_id', $job->id)->exists());
    }

    public function test_user_can_access_recommendations(): void
    {
        $user = User::where('email', 'user@jos.test')->first();

        $response = $this->actingAs($user)->get(route('user.recommendations'));
        $response->assertStatus(200);
        $response->assertSeeText('Rekomendasi Lowongan Untuk Anda');
    }

    public function test_job_original_url_always_resolves_to_valid_live_portal(): void
    {
        $jobs = Job::all();
        $this->assertNotEmpty($jobs);

        foreach ($jobs as $job) {
            $url = $job->original_url;
            $this->assertNotEmpty($url);
            $this->assertStringStartsWith('http', $url);
            $this->assertStringNotContainsString('disnaker.sumenepkab.go.id', $url);
            $this->assertStringNotContainsString('lokermadura.id', $url);
            $this->assertStringNotContainsString('infokerja.jatimprov.go.id', $url);
        }

        // Test fallback for dummy or broken URLs
        $dummyJob = new Job(['original_url' => 'https://www.kitalulus.com/lowongan/in-kabupaten-sumenep/staf-toko']);
        $this->assertEquals('https://www.kitalulus.com/lowongan/kota/kabupaten-sumenep', $dummyJob->original_url);

        $emptyJob = new Job(['original_url' => '']);
        $this->assertEquals('https://sumenepkab.go.id/berita', $emptyJob->original_url);
    }
}
