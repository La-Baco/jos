<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScrapingSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_url',
        'list_url',
        'scraper_type',
        'is_active',
        'interval_hours',
        'last_scraped_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'interval_hours' => 'integer',
            'last_scraped_at' => 'datetime',
        ];
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ScrapingLog::class)->orderBy('started_at', 'desc');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
