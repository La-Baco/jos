<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'scraping_source_id',
        'original_url',
        'url_hash',
        'content_hash',
        'title',
        'normalized_title',
        'company',
        'location',
        'normalized_regency',
        'normalized_district',
        'is_remote',
        'job_category_id',
        'employment_type',
        'education_level_id',
        'min_experience_years',
        'min_age',
        'max_age',
        'salary_min',
        'salary_max',
        'salary_notes',
        'description',
        'requirements',
        'raw_skills',
        'posted_at',
        'deadline',
        'status',
        'scraped_at',
    ];

    protected function casts(): array
    {
        return [
            'is_remote' => 'boolean',
            'min_experience_years' => 'integer',
            'min_age' => 'integer',
            'max_age' => 'integer',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'posted_at' => 'date',
            'deadline' => 'date',
            'scraped_at' => 'datetime',
        ];
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ScrapingSource::class, 'scraping_source_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_skills')
            ->withPivot('is_required')
            ->withTimestamps();
    }

    public function jobSkills(): HasMany
    {
        return $this->hasMany(JobSkill::class);
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs')
            ->withTimestamps();
    }

    public function views(): HasMany
    {
        return $this->hasMany(JobView::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(JobRecommendation::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeSumenep(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('normalized_regency', 'like', '%Sumenep%')
                ->orWhere('location', 'like', '%Sumenep%');
        });
    }

    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        if ($this->deadline && Carbon::parse($this->deadline)->isPast()) {
            return true;
        }

        return false;
    }

    public function getFormattedSalaryAttribute(): string
    {
        if ($this->salary_notes) {
            return $this->salary_notes;
        }

        if ($this->salary_min && $this->salary_max) {
            return 'Rp '.number_format($this->salary_min, 0, ',', '.').' - Rp '.number_format($this->salary_max, 0, ',', '.');
        }

        if ($this->salary_min) {
            return 'Mulai Rp '.number_format($this->salary_min, 0, ',', '.');
        }

        return 'Gaji tidak dicantumkan';
    }
}
