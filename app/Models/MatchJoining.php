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
    ];

    public function attendance()
    {
        return $this->hasOne(AttendanceMatchJoining::class, 'id_match_joining');
    }

    public function match()
    {
        return $this->belongsTo(Match::class);
    }
}