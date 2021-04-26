<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'time',
        'location',
        'sport',
        'private',
        'status',
        'created_by'
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
}
