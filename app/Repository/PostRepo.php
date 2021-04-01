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
            $friends = !empty($user->friends) ? $user->friends : [];
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
     * Get author of post
     * @param $id
     * 
     * @return json
     */
    public function getAuthor($id)
    {
        try{
            $post = $this->find($id);
            $user = $post->user;
            $name = $user->name;
            $username = $user->username;
            $avatar = $user->info->avatar;

            $result = ["name" => $name, "username" => $username, "avatar" => $avatar];
            return $this->sendSuccess($result);
        }catch(Exception $e){
            return $this->sendFailed();
        }

    }

    /**
     * Count like of post
     * @param $id
     * 
     * @return int
     */
    public function countLike($id){
        try{
            $post = $this->find($id);
            $likes = $post->likes;
            $count = $likes->count();
            return $this->sendSuccess($count);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

    
    /**
     * Count comment of post
     * @param $id
     * 
     * @return int
     */
    public function countComment($id){
        try{
            $post = $this->find($id);
            $comments = $post->comments;
            $count = $comments->count();
            return $this->sendSuccess($count);
        }catch(Exception $e){
            return $this->sendFailed($e);
        }
    }

    /**
     * Count share of post
     * @param $id
     * 
     * @return int
     */
    public function countShare($id){
        try{
            $post = $this->find($id);
            $shares = $post->shares;
            $count = $shares->count();
            return $this->sendSuccess($count);
        }catch(Exception $e){
            return $this->sendFailed($e);
        }
    }
    
    /**
     * Get images of post
     * 
     * @param id
     * @return json
     *
     */
    public function getImage($id){
        try{
            $post = $this->find($id);
            $images = $post->images;

            $result = [];
            foreach($images as $image){
                array_push($result,$image->image);
            }

            return $this->sendSuccess($result);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

    /**
     * Get user relative with post
     * 
     * @param id
     * @return array
     */
    public function getTag($id){
        try{
            $post = $this->find($id);
            $tags = $post->tags;

            $result = [];
            foreach($tags as $tag){
                $user = new \stdClass();
                $user->id = $tag->user->id;
                $user->name = $tag->user->name;
                $user->username = $tag->user->username;

                array_push($result,$user);
            }

            return $this->sendSuccess($result);
        }catch(Exception $e){
            return $this->sendFailed($e);
        }
    }

    /**
     * Get comments of post
     * @param int id 
     * @return array
     * 
     */
    public function getComments($post_id){
        try{
            $post = $this->find($post_id);
            $comments = $post->comments;
            $result = [];
            foreach($comments as $cmt)
            {
                if($cmt == null)
                continue;
                $cmt_tmp = new \stdClass();
                $cmt_tmp->username = $cmt->user->username;
                $cmt_tmp->name = $cmt->user->name;
                $cmt_tmp->avatar = $cmt->user->info == null ? null : $cmt->user->info->avatar;
                $cmt_tmp->comment = $cmt->comment;
                array_push($result,$cmt_tmp);
            }

            return $this->sendSuccess($result);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}