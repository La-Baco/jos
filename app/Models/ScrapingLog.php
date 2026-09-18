<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScrapingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'scraping_source_id',
        'started_at',
        'finished_at',
        'status',
        'jobs_found',
        'jobs_new',
        'jobs_updated',
        'duplicates',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'jobs_found' => 'integer',
            'jobs_new' => 'integer',
            'jobs_updated' => 'integer',
            'duplicates' => 'integer',
        ];
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ScrapingSource::class, 'scraping_source_id');
    }
}
