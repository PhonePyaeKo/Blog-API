<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'comment',
        'user_id',
        'post_id',
    ];

    //User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
