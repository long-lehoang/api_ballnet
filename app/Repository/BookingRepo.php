<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;

class BookingRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Booking::class;
    }
    
    public function deleteByMatch($id)
    {
        return $this->_model::where('match_id', $id)->delete();
    }
}