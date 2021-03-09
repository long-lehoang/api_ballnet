<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'user_id'
    ];

    /**
     * Get the post that owns the tag.
     */
    public function post(){
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the tag
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
