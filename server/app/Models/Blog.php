<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Blog extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = "blogs";
    protected $fillable = [
        'id',
        'title',
        'content',
        'tags',
        'photo',
        'user_id',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, "blog_id");
    }
    public function views(): HasMany
    {
        return $this->hasMany(View::class, "blog_id");
    }
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, "blog_id");
    }
}
