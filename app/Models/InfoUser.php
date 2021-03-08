<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoUser extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'info_user';

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
        'cover'
    ];

    /**
     * Get the user that owns the info.
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
