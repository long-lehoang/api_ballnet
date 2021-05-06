<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;

class Match extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'matchs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'time',
        'location',
        'sport',
        'type',
        'private',
        'status',
        'created_by',
        'team_1',
        'team_2'
    ];

    protected $appends = [
        'avatar1',
        'avatar2',
        'member1',
        'member2',
        'stadium',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function joinings()
    {
        return $this->hasMany(MatchJoining::class);
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team_1');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team_2');
    }

    public function invitations()
    {
        return $this->hasMany(MatchInvitation::class);
    }

    public function getAvatar1Attribute()
    {
        return $this->team_1 !== null ? Team::find($this->team_1)->avatar : null;
    }

    public function getAvatar2Attribute()
    {
        return $this->team_2 !== null ? Team::find($this->team_2)->avatar : null;
    }

    public function getMember1Attribute()
    {
        return $this->team_1 === null ? 0 : MatchJoining::where("team_id", $this->team_1)->count();
    }

    public function getMember2Attribute()
    {
        return $this->team_2 === null ? 0 :  MatchJoining::where("team_id", $this->team_2)->count();
    }

    public function getStadiumAttribute()
    {
        $stadium = Booking::join('stadiums', 'booking.stadium_id', '=', 'stadiums.id')->select('stadiums.name')->where('match_id', $this->id)->first();
        return $stadium == null ? null : $stadium->name;
    }
}
