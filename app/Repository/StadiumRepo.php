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
        return $this->_model::where('status', 1)->get();
    }

    public function inactive()
    {
        return $this->_model::where('status', 0)->get();
    }

    public function show($id)
    {
        $stadium = $this->find($id);
        $stadium->extensions;
        $stadium->images;
        $stadium->booking;
        return $stadium;
    }
}