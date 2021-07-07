<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\StadiumRepo;
use App\Repository\UserRepo;
use App\Http\Requests\Stadium\CreateRequest;
use App\Http\Requests\Stadium\UpdateRequest;
use App\Http\Requests\Stadium\ExtensionRequest;
use App\Http\Requests\ImageRequest;
use App\Contracts\Image;
use App\Contracts\Stadium;

use Log;
use Auth;
use Gate;

class StadiumController extends Controller
{
    protected $stdRepo;
    protected $userRepo;
    protected $imageService;
    protected $stadiumService;
    function __construct(UserRepo $userRepo, StadiumRepo $stdRepo,Stadium $stadiumService, Image $imageService)
    {
        $this->stdRepo = $stdRepo;
        $this->userRepo = $userRepo;
        $this->imageService = $imageService;
        $this->stadiumService = $stadiumService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $stadiums = $this->stdRepo->active();
        return $this->sendResponse($stadiums);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");
        
        //authorize
        Gate::authorize('lock-team');

        $stadium = $this->stdRepo->create([
            'name' => $request->name,
            'sport' => $request->sport ,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'phone' => $request->phone,
            'user_id' => Auth::id()
        ]);

        return $this->sendResponse($stadium);
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

        $stadium = $this->stdRepo->show($id);
        return $this->sendResponse($stadium);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");
        
        $stadium = $this->stdRepo->find($id);
        //authorize
        $this->authorize('update', $stadium);

        $stadium->update([
            'name' => $request->name,
            'sport' => $request->sport ,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'phone' => $request->phone,
        ]);
        $stadium->fresh();

        return $this->sendResponse($stadium);
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
        
        $stadium = $this->stdRepo->find($id);
        //authorize
        $this->authorize('delete', $stadium);

        //remove avatar
        if(!is_null($stadium->avatar)){
            if(!$this->imageService->delete($stadium->avatar)){
                Log::error("Can't Delete Images at $stadium->avatar");
            }
        }
        //remove image
        foreach ($stadium->images as $img) {
            if(!$this->imageService->delete($img->image)){
                Log::error("Can't Delete Images at $img->image");
            }
        }
        
        $stadium->delete();
        return $this->sendResponse();
    }
    
    /**
     * setAvatar
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setAvatar(ImageRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $stadium = $this->stdRepo->find($id);
        $this->authorize('update', $stadium);
        if($request->hasFile('image')){
            //upload image
            $img = $request->file('image');
            $result = $this->imageService->upload($img);
            if(!$result['success']){
                return $this->sendError(null, "Can't upload image");
            }
            //delete image
            $url = $stadium->avatar;
            if(!is_null($url)){
                $this->imageService->delete($url);
            }
            //update DB
            $stadium->avatar = $result['url'];
            $stadium->save();
            return $this->sendResponse();
        }
        return $this->sendError(null, "Image Not Found");
    }
    
    /**
     * setExtension
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setExtension(ExtensionRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $stadium = $this->stdRepo->find($id);
        $this->authorize('update', $stadium);

        $this->stadiumService->setExtension($id, $request->extensions);
        return $this->sendResponse();
    }
    
    /**
     * myStadium
     *
     * @param  mixed $id
     * @return void
     */
    public function myStadium($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //authorize
        $user = $this->userRepo->find($id);
        $this->authorize('friend', $user);
        
        return $this->sendResponse($user->stadiums);

    }
    
    /**
     * getStadiumBySport
     *
     * @param  mixed $sport
     * @return void
     */
    public function getStadiumBySport($sport)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $stadiums = $this->stdRepo->filterBySport($sport);
        return $this->sendResponse($stadiums);
    }
    
    /**
     * search
     *
     * @param  mixed $request
     * @return void
     */
    public function search(Request $request)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");
        $key = $request->search;
        $city = $request->city;
        $district = $request->district;
        $sport = $request->sport;
        Log::debug("Query: key=$key, city=$city, district=$district, sport=$sport");
        
        $location = '';
        if(!empty($district)||!empty($city))
        $location = "$district, $city";
        
        $data = $this->stadiumService->search($key, $location, $sport);
        return $this->sendResponse($data);
    }
}
