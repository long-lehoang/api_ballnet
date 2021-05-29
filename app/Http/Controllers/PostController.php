<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\PostRepo;
use App\Repository\LikeRepo;
use App\Repository\CommentRepo;
use App\Repository\ShareRepo;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\EditPostRequest;
use App\Http\Requests\Post\CommentRequest;
use Illuminate\Support\Facades\Log;
use App\Contracts\Post as PostService;
use Gate;

class PostController extends Controller
{
    protected $postRepo;
    protected $likeRepo;
    protected $commentRepo;
    protected $shareRepo;
    protected $postService;

    public function __construct(PostService $post, PostRepo $postRepo, LikeRepo $likeRepo, CommentRepo $commentRepo, ShareRepo $shareRepo)
    {
        $this->postRepo = $postRepo;
        $this->likeRepo = $likeRepo;
        $this->commentRepo = $commentRepo;
        $this->shareRepo = $shareRepo;
        $this->postService = $post;

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

        Gate::authorize('lock-post');
        
        $post = $this->postService->create($request);

        return $this->sendResponse($post->fresh());

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

        $post = $this->postRepo->find($id);
        $this->authorize('view', $post);

        $result = $this->postService->show($id);
        
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

        //authorize
        $post = $this->postRepo->find($id);

        $this->authorize('update', $post);
        Log::debug($request);
        $post = $this->postService->update($id,$request);

        return $this->sendResponse($post->images->map->image);
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

        $post = $this->postRepo->find($id);
        $this->authorize('delete', $post);

        $result = $post->delete();
       
        return $this->sendResponse();

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

        $user = Auth::guard('api')->user();
        $this->likeRepo->updateOrCreate([
            "post_id" => $id,
            "user_id" => $user->id
        ]);

        return $this->sendResponse();

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
        $this->commentRepo->forceCreate([
            "post_id" => $id,
            "user_id" => $user->id,                
            "comment" => $request->comment
        ]);
        return $this->sendResponse();

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

        $user = Auth::guard('api')->user();
        $this->shareRepo->updateOrCreate([
            "post_id" => $id,
            "user_id" => $user->id
        ]);

        return $this->sendResponse();

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