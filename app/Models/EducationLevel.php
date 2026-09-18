<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'hierarchy_order',
    ];

    public function profiles(): HasMany
    {
        return $this->hasMany(JobSeekerProfile::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
