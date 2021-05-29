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
        $comment = $this->_model::where('user_id',Auth::id())
                    ->where('post_id',$id)->first();
        return !is_null($comment);
    }
}