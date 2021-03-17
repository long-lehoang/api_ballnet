<?php
namespace App\Repository;

use Illuminate\Support\Facades\Auth;
use App\Repository\BaseRepository;
use Exception;

class ShareRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Share::class;
    }

    /**
     * UnShare a post
     * 
     * @param int $post_id
     * @param int $user_id
     * @return status
     * 
     */
    public function unShare($post_id,$user_id)
    {
        try{
            $share = $this->_model->where('post_id',$post_id)
                                ->where('user_id',$user_id)
                                ->firstOrFail();
            if ($share) {
                $share->delete();
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
    public function isShare($id){
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