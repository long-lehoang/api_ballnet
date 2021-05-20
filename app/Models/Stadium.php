<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stadiums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'sport',
        'location',
        'rating',
        'user_id'
    ];

    public function prices()
    {
        return $this->hasMany(PriceStadium::class);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ImageStadium::class);
    }
}
