<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class PostRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Post::class;
    }

    /**
     * Get post can view
     * 
     * @return json
     */
    public function getPosts()
    {
        $user = Auth::guard('api')->user();
        $posts = [] ;
        try{
            $friends = $user->friends;
            foreach($friends as $friend){
                $post = $friend->friend->posts;
                
                array_push($posts, $post);
            }
            $post = $user->posts;
            array_push($posts, $post);
            return $this->sendSuccess($posts);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

    /**
     * 
     */
}