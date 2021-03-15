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
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreatePostRequest;
use Image;

class PostController extends Controller
{
    protected $postRepo;
    protected $tagRepo;
    protected $imageRepo;
    protected $likeRepo;
    protected $commentRepo;
    protected $shareRepo;


    public function __construct(PostRepo $postRepo, TagRepo $tagRepo, ImagePostRepo $imageRepo, LikeRepo $likeRepo, CommentRepo $commentRepo, ShareRepo $shareRepo)
    {
        $this->postRepo = $postRepo;
        $this->tagRepo = $tagRepo;
        $this->imageRepo = $imageRepo;
        $this->likeRepo = $likeRepo;
        $this->commentRepo = $commentRepo;
        $this->shareRepo = $shareRepo;

        // $this->authorizeResource(Post::class,'post');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('viewAny');

        $result = $this->postRepo->getPosts();
        if($result['success']){
            return $this->sendResponse($result);
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
        try{
            // create post
            $postInput = $request->only('content','location','private');
            $postInput['user_id'] = Auth::guard('api')->user()->id;
            $post = $this->postRepo->create($postInput);
            $post_id = $post->id;

            //create tags
            $tagInputs = $request->tags;
            foreach((array)$tagInputs as $tag){
                $tagInput['tag_id'] = $tag;
                $tagInput['post_id'] = $post_id;
                $this->tagRepo->create($tagInput);
            }

            if($request->hasFile('images')){
                $fileInputs = $request->file('images');
                foreach($fileInputs as $file)
                {
                    $fileName = uniqid().time(). '.' .$file->getClientOriginalExtension();  //Provide the file name with extension 
                    $file->move(public_path().'/uploads/images/', $fileName);  
                    $this->imageRepo->create([
                        "image" => '/uploads/images/'.$fileName,
                        "post_id" => $post_id
                    ]);
                }
            }

            return $this->sendResponse();
        }catch(Exception $e){
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
        // $this->authorize('view');

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
        //return result
        $result = [
            'author' => $author['data'], 
            'like' => $like['data'], 
            'comment' => $comment['data'], 
            'share' => $share['data'], 
            'images' => $image['data'],
            'tags' => $tag['data']
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
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->postRepo->delete($id);
        if($result['success'])
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
        try{
            $user = Auth::guard('api')->user();
            $this->likeRepo->create([
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
    public function comment(Request $request, $id)
    {
        
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
        
    }

    /**
     * Share a post
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function share($id)
    {
        try{
            $user = Auth::guard('api')->user();
            $this->shareRepo->create([
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
        $user = Auth::guard('api')->user();
        $result = $this->shareRepo->unShare($id,$user->id);
        if($result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }
}
