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
        'latitude',
        'longitude',
        'phone',
        'avatar',
        'rating',
        'user_id'
    ];

    protected $appends = [
        'reviews',
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

    public function extensions()
    {
        return $this->hasMany(ExtensionStadium::class);
    }

    public function getReviewsAttribute()
    {
        $reviews = $this->booking->map(function($book){
            if($book->rating == 0){
                return null;
            }
            $obj = new \stdClass;
            $obj->id = $book->id;
            $obj->name = $book->user->name;
            $obj->username = $book->user->username;
            $obj->avatar = $book->user->info->avatar;
            $obj->feedback = $book->feedback;
            $obj->rating = $book->rating;
            $obj->time = $book->feedback;
            return $obj;
        });

        return array_values(array_filter($reviews->toArray()));
    }
}
