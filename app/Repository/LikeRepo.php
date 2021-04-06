<?php
namespace App\Repository;

use App\Repository\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Check user if like post
     * @param $id
     * 
     * @return int
     */
    public function isLike($id){
        $user_id = Auth::guard('api')->user()->id;
        try{
            $this->_model::where('user_id',$user_id)
                        ->where('post_id',$id)->firstOrFail();
            return $this->sendSuccess();
        }catch(Exception $e){
            return $this->sendFailed($e);
        }
    }

    public function updateOrCreate($data)
    {
        try{
            $result = $this->_model::withTrashed()->where($data)->first();
            if($result){
                $result->restore();
            }else{
                $this->_model::updateOrCreate($data);
            }
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}