<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * info
     *
     * @return void
     */
    public function info()
    {
        return $this->hasOne(Info::class);
    }
    
    /**
     * posts
     *
     * @return void
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
        
    /**
     * tags
     *
     * @return void
     */
    public function tags()
    {
        return $this->hasMany(Tag::class,'tag_id');
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'user_id');
    }
    /**
     * friends
     *
     * @return void
     */
    public function friends()
    {
        return $this->hasMany(Friend::class);
    }
        
    /**
     * friendRequests
     *
     * @return void
     */
    public function friendRequests()
    {
        return $this->hasMany(FriendRequest::class);
    }
    
    /**
     * following
     *
     * @return void
     */
    public function following()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }
    
    /**
     * follower
     *
     * @return void
     */
    public function follower()
    {
        return $this->hasMany(Follow::class, 'id_follow');
    }
        
    /**
     * sports
     *
     * @return void
     */
    public function sports(){
        return $this->hasMany(Sport::class);
    }
    
    /**
     * teams
     *
     * @return void
     */
    public function teams(){
        return $this->hasMany(MemberTeam::class, 'member_id');
    }
    
    /**
     * captainTeams
     *
     * @return void
     */
    public function captainTeams(){
        return $this->hasMany(Team::class, 'id_captain');
    }
    
    /**
     * adminTeams
     *
     * @return void
     */
    public function adminTeams(){
        return $this->hasMany(AdminTeam::class, 'admin_id');
    }

    public function matchs(){
        return $this->hasMany(MatchJoining::class, 'player_id');
    }

    public function stadiums(){
        return $this->hasMany(Stadium::class);
    }
}