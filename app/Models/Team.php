<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'location',
        'sport',
        'avatar',
        'cover',
        'overview',
        'id_captain'
    ];

    public function captain(){
        return $this->belongsTo(User::class, 'id_captain');
    }

    public function members(){
        return $this->hasMany(MemberTeam::class, 'team_id');
    }

    public function admins(){
        return $this->hasMany(AdminTeam::class, 'team_id');
    }

    public function posts(){
        return $this->hasMany(Post::class, 'team_id');
    }
}
