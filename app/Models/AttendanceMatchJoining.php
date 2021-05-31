<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceMatchJoining extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendance_match_joining';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_match_joining',
        'user_id',
        'rating',
        'attendance',
    ];

    public function match_joining()
    {
        return $this->belongsTo(MatchJoining::class,'id_match_joining');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
