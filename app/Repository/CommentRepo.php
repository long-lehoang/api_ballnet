<?php
namespace App\Repository;

use Illuminate\Support\Facades\Auth;
use App\Repository\BaseRepository;
use Exception;

class CommentRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Comment::class;
    }

    /**
     * Check user if like post
     * @param $id
     * 
     * @return int
     */
    public function isComment($id){
        $user_id = Auth::guard('api')->user()->id;
        try{
            $this->_model::where('user_id',$user_id)
                        ->where('post_id',$id)->firstOrFail();
            return $this->sendSuccess();
        }catch(Exception $e){
            return $this->sendFailed($e);
        }
    }
}