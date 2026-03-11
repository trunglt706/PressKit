<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ArticleSeo extends Model
{
    protected $fillable = [
        'article_id',
        'title',
        'description',
        'keywords',
        'og_image',
        'score',
        'score_breakdown',
        'warnings',
        'last_analyzed_at',
    ];

    protected function casts(): array
    {
        return [
            'score_breakdown' => 'array',
            'warnings' => 'array',
            'last_analyzed_at' => 'datetime',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
