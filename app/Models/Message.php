<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'user_id',
        'room_id',
    ];

    protected $appends = [
        "username",
        "avatar",
    ];

    public function getUsernameAttribute()
    {
        $user = User::where("id", $this->user_id)->first();
        return is_null($user) ? '' : $user->username;
    }

    public function getAvatarAttribute()
    {
        $user = User::where("id", $this->user_id)->first();
        return is_null($user) ? '' : $user->avatar;
    }

    public function setMessageAttribute($message)
    {
        $this->attributes['message'] = Crypt::encryptString($message);
    }

    public function getMessageAttribute($message)
    {
        return Crypt::decryptString($message);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
