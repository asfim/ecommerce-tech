<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body',
        'image',
        'is_active',
        'views',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'views' => 'integer',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        parent::booted();

        static::saving(function (BlogPost $post) {
            if (empty($post->slug) || $post->isDirty('title')) {
                $post->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $post->title)));
            }
        });
    }
}
