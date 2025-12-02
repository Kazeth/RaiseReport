<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    /** @use HasFactory<\Database\Factories\ThreadFactory> */
    use HasFactory;

    protected $fillable = [
        'threadName',
        'threadContent',
        'threadUpvote',
        'threadStatus'
    ];

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function upvoters():HasMany
    {
        return $this->hasMany(User::class);
    }
}
