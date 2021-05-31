<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchInvitation extends Model
{
    use HasFactory;

    /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
    protected $fillable = [
        'team_id',
        'match_id',
        'status',
        'invited_by',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
