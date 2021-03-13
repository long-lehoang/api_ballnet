<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\PostRepo;
use App\Repository\ImagePostRepo;
use App\Repository\TagRepo;
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


    public function __construct(PostRepo $postRepo, TagRepo $tagRepo, ImagePostRepo $imageRepo)
    {
        $this->postRepo = $postRepo;
        $this->tagRepo = $tagRepo;
        $this->imageRepo = $imageRepo;

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
        //count like
        //count comment
        //count share

        //get info author
        
        return $this->sendResponse();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
