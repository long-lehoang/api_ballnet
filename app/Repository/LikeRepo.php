<?php
namespace App\Repository;

use App\Repository\BaseRepository;
use Exception;

class LikeRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Like::class;
    }

    /**
     * Dislike
     * 
     * @param int $post_id
     * @param int $user_id
     * @return status
     * 
     */
    public function unLike($post_id,$user_id)
    {
        try{
            $like = $this->_model->where('post_id',$post_id)
                                ->where('user_id',$user_id)
                                ->firstOrFail();
            if ($like) {
                $like->delete();
                return $this->sendSuccess();
            }
    
            return $this->sendFailed();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}