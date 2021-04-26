<?php

namespace App\Repository;

use App\Repository\BaseRepository;

class StadiumRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Stadium::class;
    }

    public function active()
    {
        return $this->_model::where('status', 'active')->get();
    }

    public function waiting()
    {
        return $this->_model::where('status', 'waiting')->get();
    }
}