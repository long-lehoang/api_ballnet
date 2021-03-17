<?php
namespace App\Repository;

use Exception;
use App\Repository\BaseRepository;

class TagRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Tag::class;
    }
    
    /**
     * Delete all tag in post
     * @param int id post
     * @return bool
     * 
     */
    public function deleteAll($id){
        try{
            $this->findByCondition("post_id",$id)->delete();
            return $this->sendSuccess();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}