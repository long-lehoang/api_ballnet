<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected $appends = [
        'last_message',
        'username',
        'avatar',
    ];

    public function getNameAttribute($name)
    {
        if($name == 'Friend Chat')
        {
            $friend = UserRoom::where([['room_id',$this->id],['user_id', '<>', Auth::id()]])->first();
            if(is_null($friend)){
                return 'No Name';
            }else{
                return $friend->user->name;
            }
        }else{
            return $name;
        }
    }

    public function getUsernameAttribute()
    {
        //TODO: not support for group
        $friend = UserRoom::where([['room_id',$this->id],['user_id', '<>', Auth::id()]])->first();
        if(is_null($friend)){
            return '';
        }else{
            return $friend->user->username;
        }
    }

    public function getLastMessageAttribute()
    {
        $message =  Message::where('room_id', $this->id)->orderBy("created_at", "desc")->first();
        return $message;
    }

    public function getAvatarAttribute()
    {
        $userInRoom =  DB::table('user_room')->where([['room_id', $this->id],['user_id','<>',Auth::id()]])->get();
        if(count($userInRoom) == 1){
            $user = DB::table('infos')->where('user_id',$userInRoom[0]->user_id)->first();
            return $user->avatar;
        }else{
            return null;
        }
    }

    public function users()
    {
        return $this->hasMany(UserRoom::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
