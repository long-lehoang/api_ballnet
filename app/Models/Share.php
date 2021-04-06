<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Share extends Model
{
    use HasFactory, SoftDeletes;

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
     * Get the post that owns the share.
     */
    public function post(){
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the share
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
