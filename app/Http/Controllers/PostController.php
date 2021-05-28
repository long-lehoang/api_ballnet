<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\PostRepo;
use App\Repository\ImagePostRepo;
use App\Repository\TagRepo;
use App\Repository\LikeRepo;
use App\Repository\CommentRepo;
use App\Repository\ShareRepo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\EditPostRequest;
use App\Http\Requests\Post\CommentRequest;
use Illuminate\Support\Facades\Log;
use App\Contracts\Post as PostService;

use Image;
use Exception;

class PostController extends Controller
{
    protected $postRepo;
    protected $tagRepo;
    protected $imageRepo;
    protected $likeRepo;
    protected $commentRepo;
    protected $shareRepo;
    protected $postService;

    public function __construct(PostService $post, PostRepo $postRepo, TagRepo $tagRepo, ImagePostRepo $imageRepo, LikeRepo $likeRepo, CommentRepo $commentRepo, ShareRepo $shareRepo)
    {
        $this->postRepo = $postRepo;
        $this->tagRepo = $tagRepo;
        $this->imageRepo = $imageRepo;
        $this->likeRepo = $likeRepo;
        $this->commentRepo = $commentRepo;
        $this->shareRepo = $shareRepo;
        $this->postService = $post;
        // $this->authorizeResource(Post::class,'post');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $result = $this->postRepo->getPosts();
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        try{
            // create post
            $postInput = $request->only('content','location','private','team_id');
            $postInput['user_id'] = Auth::guard('api')->user()->id;
            $post = $this->postRepo->create($postInput);
            $post_id = $post->id;

            //create tags
            $tagInputs = $request->tags;
            $tags = explode(",",$tagInputs);
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
                if(!$image['success'])
                return $this->sendError();
            }

            return $this->sendResponse($post->fresh());
        }catch(Exception $e){
            Log::error($e->getMessage());
            return $this->sendError();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //get post
        try{
            $post = $this->postRepo->find($id);
        }catch(Exception $e){
            return $this->response404();
        }
        //get info author
        $author = $this->postRepo->getAuthor($id);
        if(!$author['success'])
            return $this->sendError($author['message']);
        //count like
        $like = $this->postRepo->countLike($id);
        if(!$like['success'])
            return $this->sendError($like['message']);
        //count comment
        $comment = $this->postRepo->countComment($id);
        if(!$comment['success'])
            return $this->sendError($comment['message']);
        //count share
        
        $share = $this->postRepo->countShare($id);
        if(!$share['success'])
        return $this->sendError($share['message']);

        //get image
        $image = $this->postRepo->getImage($id);
        if(!$image['success'])
        return $this->sendError($image['message']);

        //get tag
        $tag = $this->postRepo->getTag($id);
        if(!$tag['success'])
        return $this->sendError($tag['message']);

        $isLike = $this->likeRepo->isLike($id);
        $isComment = $this->commentRepo->isComment($id);
        $isShare = $this->shareRepo->isShare($id);

        //return result
        $result = [
            'post' => $post,
            'author' => $author['data'], 
            'like' => $like['data'], 
            'comment' => $comment['data'], 
            'share' => $share['data'], 
            'images' => $image['data'],
            'tags' => $tag['data'],
            'isLike' => $isLike['success'],
            'isComment' => $isComment['success'],
            'isShare' => $isShare['success']
        ];
        
        return $this->sendResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditPostRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $data = $request->all();
        //update
        $update = $this->postRepo->update($id,[
            "private" => $request->private,
            "content" => $request->content,
            "location" => $request->location
        ]);
        if(!$update){
            return $this->sendError();
        }

        //update tags
        try{
            $post = $this->postRepo->find($id)->tags()->delete();
            $tagInputs = $request->tags;
            $tags = explode(",",$tagInputs);
            foreach($tags as $tag){
                if($tag == "")
                continue;
                $result = $this->tagRepo->create([
                    "post_id" => $id,
                    "tag_id" => $tag
                ]);
            }
        }catch(Exception $e){
            return $this->sendError();
        }
        //update image
        try{
            $this->postRepo->find($id)->images()->delete();
            if($request->hasFile('images')){
                $fileInputs = $request->file('images');
                $image = $this->imageRepo->upload($fileInputs);
                if(!$image)
                return $this->sendError();
            }
        }catch(Exception $e){
            return $this->sendError();
        }

        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $result = $this->postRepo->delete($id);
        if($result)
            return $this->sendResponse();
        else
            return $this->sendError();
    }

    /**
     * Like a post
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function like($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        try{
            $user = Auth::guard('api')->user();
            $this->likeRepo->updateOrCreate([
                "post_id" => $id,
                "user_id" => $user->id
            ]);

            return $this->sendResponse();
        }catch(Exception $e){
            return $this->sendError();
        }
    }

    /**
     * Dislike a post
     * @param int post_id
     * @return \Illuminate\Http\Response
     * 
     */
    public function unLike($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $user = Auth::guard('api')->user();
        $result = $this->likeRepo->unLike($id,$user->id);
        if($result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }

    /**
     * Comment a post
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function comment(CommentRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $user = Auth::guard('api')->user();
        try{
            $this->commentRepo->forceCreate([
                "post_id" => $id,
                "user_id" => $user->id,                
                "comment" => $request->comment
            ]);
            return $this->sendResponse();
        }catch(Exception $e){
            return $this->sendError();
        }
    }

    /**
     * Get list comment
     * @param int id
     * @return \Illuminate\Http\Response
     */
    public function getComments($post_id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $result = $this->postRepo->getComments($post_id);
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }
    /**
     * Comment a post
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function unComment($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $delete = $this->commentRepo->delete($id);
        if($delete){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }

    /**
     * Share a post
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function share($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        try{
            $user = Auth::guard('api')->user();
            $this->shareRepo->updateOrCreate([
                "post_id" => $id,
                "user_id" => $user->id
            ]);

            return $this->sendResponse();
        }catch(Exception $e){
            return $this->sendError();
        }
    }

    /**
     * UnShare a post
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function unShare($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $user = Auth::guard('api')->user();
        $result = $this->shareRepo->unShare($id,$user->id);
        if($result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }

}