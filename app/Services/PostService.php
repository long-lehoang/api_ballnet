<?php

namespace App\Services;

use App\Contracts\Post;
use Illuminate\Support\Facades\Auth;
use App\Repository\UserRepo;
use Exception;
use Log;
class PostService implements Post{
    protected $user;
    
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }
    
    public function getMyPost()
    {
        $user = Auth::guard('api')->user();
        $posts = [];
        try{

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

            return [
                "success" => true,
                "data" => $posts
            ];
        }catch(Exception $e){
            Log::info(__CLASS__.' -> '.__FUNCTION__.' -> '.__LINE__.':'.$e->getMessage());
            return [
                "success" => false,
                "message" => $e->getMessage(),
            ];
        }

    }
}