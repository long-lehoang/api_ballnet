<?php

namespace App\Repository;

use App\Repository\BaseRepository;

class ExtensionStadiumRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\ExtensionStadium::class;
    }

}