<?php

namespace App\Services;

use App\Contracts\Post;
use App\Repository\UserRepo;
use App\Repository\PostRepo;
use App\Repository\TagRepo;
use App\Repository\CommentRepo;
use App\Repository\ShareRepo;
use App\Repository\ImagePostRepo;
use App\Repository\LikeRepo;
use Log;
use Auth;

class PostService implements Post{
    protected $user;
    protected $postRepo;
    protected $tagRepo;
    protected $shareRepo;
    protected $commentRepo;
    protected $likeRepo;
    protected $image;
    protected $imageRepo;
    
    public function __construct(UserRepo $user, 
                                PostRepo $postRepo, 
                                TagRepo $tagRepo, 
                                ImageService $image,
                                CommentRepo $commentRepo,
                                ShareRepo $shareRepo,
                                LikeRepo $likeRepo,
                                ImagePostRepo $imageRepo)
    {
        $this->user = $user;
        $this->postRepo = $postRepo;
        $this->tagRepo = $tagRepo;
        $this->image = $image;
        $this->likeRepo = $likeRepo;
        $this->commentRepo = $commentRepo;
        $this->shareRepo = $shareRepo;
        $this->imageRepo = $imageRepo;
    }
        
    /**
     * getPostOfUser
     *
     * @param  mixed $id
     * @return void
     */
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
        $shares = $user->shares->map->post;
        array_push($posts, $shares);

        return $posts;

    }
    
    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        //get post
        $post = $this->postRepo->find($id);
        $author = $this->postRepo->getAuthor($id);
        $like = $this->postRepo->countLike($id);
        $comment = $this->postRepo->countComment($id);
        $share = $this->postRepo->countShare($id);
        $image = $this->postRepo->getImage($id);

        //get tag
        $tag = $this->postRepo->getTag($id);
        $isLike = $this->likeRepo->isLike($id);
        $isComment = $this->commentRepo->isComment($id);
        $isShare = $this->shareRepo->isShare($id);

        //return result
        $result = [
            'post' => $post,
            'author' => $author, 
            'like' => $like, 
            'comment' => $comment, 
            'share' => $share, 
            'images' => $image,
            'tags' => $tag,
            'isLike' => $isLike,
            'isComment' => $isComment,
            'isShare' => $isShare
        ];

        return $result;
    }
    
    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $request
     * @return void
     */
    public function update($id, $request)
    {
        Log::debug($request);
        $post = $this->postRepo->find($id);

        $post->private = $request->private;
        $post->content = $request->content;
        $post->location = $request->location;
        $post->save();

        //update tags
        $post->tags()->delete();
        $tags = explode(",",$request->tags);

        foreach($tags as $tag){
            if($tag == "")
            continue;
            $this->tagRepo->create([
                "post_id" => $id,
                "tag_id" => $tag
            ]);
        }

        //delete image
        foreach ($post->images as $image) {
            if($this->image->delete($image->image))
            $image->delete();
        }

        //upload images
        if($request->hasFile('images')){
            $fileInputs = $request->file('images');
            $image = $this->imageRepo->upload($id, $fileInputs);
        }

        return $post->fresh();
    }
    
    /**
     * create
     *
     * @param  mixed $request
     * @return void
     */
    public function create($request)
    {
        // create post
        $postInput = $request->only('content','location','private','team_id');
        $postInput['user_id'] = Auth::id();
        $post = $this->postRepo->forceCreate($postInput);
        $post_id = $post->id;

        //create tags
        $tags = explode(",",$request->tags);
        foreach($tags as $tag){
            if($tag=="")
            continue;
            $tagInput['tag_id'] = $tag;
            $tagInput['post_id'] = $post_id;
            $this->tagRepo->create($tagInput);
        }

        //create image
        if($request->hasFile('images')){
            $fileInputs = $request->file('images');
            $image = $this->imageRepo->upload($post_id, $fileInputs);
        }

        return $post;
    }
}