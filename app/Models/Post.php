<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie',
        'poster',
        'upvote',
        'downvote',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
