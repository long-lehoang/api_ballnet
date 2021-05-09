<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchJoining extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'match_joining';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
    protected $fillable = [
        'team_id',
        'match_id',
        'player_id',
        'invited_by',
        'status',
    ];

    public function attendance()
    {
        return $this->hasOne(AttendanceMatchJoining::class, 'id_match_joining');
    }

    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
