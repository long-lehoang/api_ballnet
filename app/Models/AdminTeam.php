<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTeam extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_team';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
    protected $fillable = [
        'team_id',
        'admin_id',
    ];

    public function team(){
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id');
    }
}
