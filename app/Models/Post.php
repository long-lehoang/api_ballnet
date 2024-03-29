<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'private',
        'content',
        'location',
        'user_id',
        'team_id'
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team that owns the post.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    /**
     * Get the likes for the blog post
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the comments for the blog post
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the shares for the blog post
     */
    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    /**
     * Get the tags for the blog post
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Get image of post
     */
    public function images()
    {
        return $this->hasMany(ImagePost::class);
    }
}
