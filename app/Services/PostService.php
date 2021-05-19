<?php

namespace App\Services;

use App\Contracts\Post;
use App\Repository\UserRepo;

class PostService implements Post{
    protected $user;
    
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }
    
    public function getPostOfUser($id)
    {
        $user = $this->user->find($id);
        $posts = [];

        //my post
        $myPost = $user->posts()->whereNotIn('private', ['Team'])->get();
        array_push($posts, $myPost);

        //get tags post
        $tagPost = [];
        $tags = $user->tags;

        foreach ($tags as $key => $tag) {
            # code...
            $post = $tag->post()->whereIn('private',['Public', 'Friend'])->get();

            array_push($tagPost, $post);
        }
        array_push($posts, $tagPost);

        //get share post
        $sharePost = [];
        $shares = $user->shares;
        foreach ($shares as $key => $share) {
            # code...
            $post = $share->post()->whereIn('private',['Public', 'Friend'])->get();
            array_push($sharePost, $post);
        }
        array_push($posts, $sharePost);

        return $posts;

    }
}