<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\StadiumRepo;
use App\Http\Requests\Stadium\CreateRequest;
use App\Http\Requests\Stadium\UpdateRequest;
use Log;
use Auth;

class StadiumController extends Controller
{
    protected $stdRepo;
    function __construct(StadiumRepo $stdRepo)
    {
        $this->stdRepo = $stdRepo;
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
        
        $stadium = $this->stdRepo->create([
            'name' => $request->name,
            'sport' => $request->sport ,
            'location' => $request->location,
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

        $stadium = $this->stdRepo->find($id);
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

        $stadium->delete();
        return $this->sendResponse();
    }
}
