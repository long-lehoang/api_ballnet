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
        return User::where("id", $this->user_id)->first()->username;
    }

    public function getAvatarAttribute()
    {
        return Info::where("user_id", $this->user_id)->first()->avatar;
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
