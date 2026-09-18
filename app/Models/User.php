<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isJobSeeker(): bool
    {
        return $this->role === 'job_seeker';
    }

    public function profile(): HasOne
    {
        return $this->hasOne(JobSeekerProfile::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('proficiency_level')
            ->withTimestamps();
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class)->orderBy('start_year', 'desc');
    }

    public function preferredCategories(): BelongsToMany
    {
        return $this->belongsToMany(JobCategory::class, 'user_job_preferences')
            ->withTimestamps();
    }

    public function savedJobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'saved_jobs')
            ->withTimestamps();
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(JobRecommendation::class);
    }

    /**
     * Calculate profile completion percentage (0 - 100).
     */
    public function profileCompletionPercentage(): int
    {
        $percentage = 20; // base registered account (name, email)

        $profile = $this->profile;
        if ($profile) {
            if (! empty($profile->phone) && ! empty($profile->birth_date) && ! empty($profile->gender)) {
                $percentage += 15;
            }
            if (! empty($profile->district) || ! empty($profile->village)) {
                $percentage += 15;
            }
            if (! empty($profile->education_level_id)) {
                $percentage += 15;
            }
            if ($this->preferredCategories()->count() > 0) {
                $percentage += 10;
            }
        }

        if ($this->skills()->count() > 0) {
            $percentage += 15;
        }

        if ($this->experiences()->count() > 0) {
            $percentage += 10;
        }

        return min(100, $percentage);
    }

    /**
     * Determine whether the profile is considered complete enough for matching.
     */
    public function hasCompletedProfile(): bool
    {
        $profile = $this->profile;
        if (! $profile) {
            return false;
        }

        return ! empty($profile->education_level_id) &&
            (! empty($profile->regency) || ! empty($profile->district)) &&
            $this->skills()->count() > 0;
    }
}
