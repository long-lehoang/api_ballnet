<?php
namespace App\Repository;

use App\Repository\BaseRepository;
use Exception;

class CommentRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\ImagePost::class;
    }

}