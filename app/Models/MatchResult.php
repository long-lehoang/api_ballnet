<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchResult extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'match_result';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reviewer_id',
        'rating',
        'match_id',
        'result',
        'opponent_team_id',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    public function opponent()
    {
        return $this->belongsTo(Team::class,'opponent_team_id');
    }
}
