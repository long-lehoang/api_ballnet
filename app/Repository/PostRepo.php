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
                $post = $friend->friend->posts()->whereIn('private', ['Public', 'Friend'])->get();                
                array_push($posts, $post);
            }
            $post = $user->posts()->whereNotIn('private', ['Team'])->get();
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
        $post = $this->find($id);
        $user = $post->user;
        $name = $user->name;
        $username = $user->username;
        $avatar = $user->info->avatar;

        $result = ["name" => $name, "username" => $username, "avatar" => $avatar];
        return $result;

    }

    /**
     * Count like of post
     * @param $id
     * 
     * @return int
     */
    public function countLike($id){
        $post = $this->find($id);
        $likes = $post->likes;
        $count = $likes->count();
        return $count;
    }

    
    /**
     * Count comment of post
     * @param $id
     * 
     * @return int
     */
    public function countComment($id){
        $post = $this->find($id);
        $comments = $post->comments;
        $count = $comments->count();
        return $count;

    }

    /**
     * Count share of post
     * @param $id
     * 
     * @return int
     */
    public function countShare($id){
        $post = $this->find($id);
        $shares = $post->shares;
        $count = $shares->count();
        return $count;
    }
    
    /**
     * Get images of post
     * 
     * @param id
     * @return json
     *
     */
    public function getImage($id){
        $post = $this->find($id);
        $images = $post->images;

        $result = [];
        foreach($images as $image){
            array_push($result,$image->image);
        }
        return $result;
    }

    /**
     * Get user relative with post
     * 
     * @param id
     * @return array
     */
    public function getTag($id){
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

        return $result;
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