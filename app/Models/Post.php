<?php

namespace App\Models;

use Dom\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    //User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Comment
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
