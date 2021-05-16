<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;
use Auth;

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
        'avatar_user',
        'name_user',
        'avatar1',
        'avatar2',
        'name1',
        'name2',
        'member1',
        'member2',
        'admin1',
        'admin2',
        'captain1',
        'captain2',
        'stadium',
        'join1',
        'join2',
        'wait1',
        'wait2',
        'idRequest1',
        'idRequest2',
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
        return $this->team_1 === null ? 0 : MatchJoining::where([["team_id", $this->team_1],["match_id", $this->id],["status","active"]])->count();
    }

    public function getMember2Attribute()
    {
        return $this->team_2 === null ? 0 :  MatchJoining::where([["team_id", $this->team_2],["match_id", $this->id],["status","active"]])->count();
    }

    public function getStadiumAttribute()
    {
        $stadium = Booking::join('stadiums', 'booking.stadium_id', '=', 'stadiums.id')->select('stadiums.name')->where('match_id', $this->id)->first();
        return $stadium == null ? null : $stadium->name;
    }

    public function getJoin1Attribute()
    {
        $join = MatchJoining::where([
            ["match_id", $this->id],
            ['player_id', Auth::id()],
            ['team_id', $this->team_1],
            ['status', 'active']
        ])->first();
        return !is_null($join);
    }

    public function getJoin2Attribute()
    {
        $join = MatchJoining::where([
            ["match_id", $this->id],
            ['player_id', Auth::id()],
            ['team_id', $this->team_2],
            ['status', 'active']
        ])->first();
        return !is_null($join);
    }

    public function getWait1Attribute()
    {
        $join = MatchJoining::where([
            ["match_id", $this->id],
            ['team_id', $this->team_1],
            ['player_id', Auth::id()],
            ['status', 'waiting']
        ])->first();
        if(is_null($join)) return 0;

        if(is_null($join->invited_by)){
            return 1;
        }else{
            return 2;
        }
    }

    public function getWait2Attribute()
    {
        $join = MatchJoining::where([
            ["match_id", $this->id],
            ['team_id', $this->team_2],
            ['player_id', Auth::id()],
            ['status', 'waiting']
        ])->first();
        if(is_null($join)) return 0;

        if(is_null($join->invited_by)){
            return 1;
        }else{
            return 2;
        }
    }

    public function getAdmin1Attribute()
    {
        $admin = AdminTeam::where([
            ['admin_id', Auth::id()],
            ['team_id', $this->team_1]
        ])->first();

        $captain = Team::where([
            ['id', $this->team_1],
            ['id_captain', Auth::id()]
        ])->first();

        return !is_null($admin)||!is_null($captain);
    }

    public function getAdmin2Attribute()
    {
        $admin = AdminTeam::where([
            ['admin_id', Auth::id()],
            ['team_id', $this->team_2]
        ])->first();

        $captain = Team::where([
            ['id', $this->team_2],
            ['id_captain', Auth::id()]
        ])->first();

        return !is_null($admin)||!is_null($captain);
    }

    public function getIdRequest1Attribute()
    {
        $join = MatchJoining::where([
            ["match_id", $this->id],
            ['team_id', $this->team_1],
            ['player_id', Auth::id()],
        ])->first();

        if(is_null($join)){
            return null;
        }else{
            return $join->id;
        }
    }

    public function getIdRequest2Attribute()
    {
        $join = MatchJoining::where([
            ["match_id", $this->id],
            ['team_id', $this->team_2],
            ['player_id', Auth::id()],
        ])->first();

        if(is_null($join)){
            return null;
        }else{
            return $join->id;
        }
    }

    public function getName1Attribute()
    {
        $team = Team::find($this->team_1);
        return is_null($team) ? null : $team->name;
    }

    public function getName2Attribute()
    {
        $team = Team::find($this->team_2);
        return is_null($team) ? null : $team->name;
    }

    public function getCaptain1Attribute()
    {
        $team = Team::find($this->team_1);
        return !is_null($team)&&$team->id_captain === Auth::id();
    }

    public function getCaptain2Attribute()
    {
        $team = Team::find($this->team_2);
        return !is_null($team)&&$team->id_captain === Auth::id();
    }

    public function getAvatarUserAttribute()
    {
        return $this->createdBy->info->avatar;
    }

    public function getNameUserAttribute()
    {
        return $this->createdBy->name;
    }
}
