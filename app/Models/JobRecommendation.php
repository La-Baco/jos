<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'total_score',
        'skill_score',
        'education_score',
        'location_score',
        'experience_score',
        'interest_score',
        'age_score',
        'score_breakdown',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'total_score' => 'integer',
            'skill_score' => 'integer',
            'education_score' => 'integer',
            'location_score' => 'integer',
            'experience_score' => 'integer',
            'interest_score' => 'integer',
            'age_score' => 'integer',
            'score_breakdown' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
