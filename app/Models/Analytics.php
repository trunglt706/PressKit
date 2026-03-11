<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    protected $fillable = [
        'article_id',
        'views',
        'likes',
        'shares',
        'tracked_date',
    ];

    protected function casts(): array
    {
        return [
            'tracked_date' => 'date',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
