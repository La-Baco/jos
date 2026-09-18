<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSeekerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'birth_date',
        'gender',
        'province',
        'regency',
        'district',
        'village',
        'education_level_id',
        'major',
        'graduation_year',
        'willing_outside_sumenep',
        'willing_outside_madura',
        'willing_remote',
        'expected_salary_min',
        'preferred_location',
        'bio',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'willing_outside_sumenep' => 'boolean',
            'willing_outside_madura' => 'boolean',
            'willing_remote' => 'boolean',
            'expected_salary_min' => 'decimal:2',
            'graduation_year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    /**
     * Get age calculated from birth_date.
     */
    public function getAgeAttribute(): ?int
    {
        if (! $this->birth_date) {
            return null;
        }

        return Carbon::parse($this->birth_date)->age;
    }
}
