<?php

namespace App\Services;

use App\Contracts\Post;
use App\Repository\UserRepo;
use Exception;

class PostService implements Post{
    protected $user;
    
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }
    
    public function getPostByUser($username)
    {
        $posts = [];
        try{
            $user = $this->user->findUser($username);
            if(!$user['success'])
            {
                return [
                    'success' => false,
                    'data' => null
                ];
            }
            $user = $user['data'];

            //my post
            $myPost = $user->posts;
            array_push($posts, $myPost);

            //get tags post
            $tagPost = [];
            $tags = !empty($user->tags) ? $user->tags : [];
            foreach ($tags as $key => $tag) {
                # code...
                $post = $tag->post;
                array_push($tagPost, $post);
            }
            array_push($posts, $tagPost);
            
            //get share post
            $sharePost = [];
            $shares = !empty($user->shares) ? $user->shares : [];
            foreach ($shares as $key => $share) {
                # code...
                $post = $share->post;
                array_push($sharePost, $post);
            }
            array_push($posts, $sharePost);

            return [
                "success" => true,
                "data" => $posts
            ];
        }catch(Exception $e){
            return [
                "success" => false,
                "message" => $e->message
            ];
        }

    }
}