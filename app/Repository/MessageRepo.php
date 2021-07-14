<?php
namespace App\Repository;

use Exception;
use App\Repository\BaseRepository;

class MessageRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Message::class;
    }
    
}