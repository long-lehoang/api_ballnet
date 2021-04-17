<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberTeam extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'member_team';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'member_id',
        'invited_by',
        'status',
    ];

    public function team(){
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function member(){
        return $this->belongsTo(User::class, 'member_id');
    }

    public function invitedBy(){
        return $this->belongsTo(User::class, 'invited_by');
    }
}
