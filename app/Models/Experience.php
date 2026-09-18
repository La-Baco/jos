<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_title',
        'company_name',
        'start_year',
        'end_year',
        'is_current',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'start_year' => 'integer',
            'end_year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate duration in years.
     */
    public function getDurationYearsAttribute(): float
    {
        $endYear = $this->is_current ? (int) date('Y') : ($this->end_year ?: (int) date('Y'));
        $diff = $endYear - $this->start_year;

        return max(0.5, (float) $diff);
    }
}
