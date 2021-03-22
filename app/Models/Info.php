<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'birthday',
        'sex',
        'avatar',
        'address',
        'phone',
        'status',
        'overview',
        'cover',
        'points'
    ];
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'points' => 0,
    ];
    /**
     * Get the user that owns the info.
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}