<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests\MatchJoining\CreateRequest;
use App\Repository\MatchRepo;
use App\Repository\MatchJoiningRepo;
use App\Contracts\Match;
use Log;

class MatchJoiningController extends Controller
{
    protected $matchRepo;
    protected $matchService;
    protected $matchJoining;

    function __construct(MatchRepo $matchRepo, Match $matchService, MatchJoiningRepo $matchJoining)
    {
        $this->matchRepo = $matchRepo;
        $this->matchService = $matchService;
        $this->matchJoining = $matchJoining;
    }
    /**
     * Display a matches joining of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $matchJoinings = Auth::guard('api')->user()->matchs;
        return $this->sendResponse($matchJoinings);
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

        $match = $this->matchRepo->find($request->match_id);
        //authorize
        $this->authorize('userJoin', $match);

        //save request
        $join = $this->matchService->userJoin($request->match_id, $request->team_id, $request->player_id);
        
        //response
        return $this->sendResponse($join);
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

        $match = $this->matchRepo->find($id);
        $joinings = $match->joinings->filter(function($join){
            return $join->status === 'active';
        });
        return $this->sendResponse($joinings);
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
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $joining = $this->matchJoining->find($id);
        //authorize
        $this->authorize('updateJoining', $joining);
        
        //update
        if($joining->status == 'invited' || $joining->status == 'requested'){
            $joining->status = 'active';
        }else{
            $joining->status = 'requested';
        }
        $joining->save();

        //response
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

        $joining = $this->matchJoining->find($id);
        //authorize
        $this->authorize('deleteJoining', $joining);
        //delete
        $joining->delete();

        return $this->sendResponse();
    }

    public function getFriendNotInMatch($match_id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $people = $this->matchService->getFriendNotInMatch($match_id);
        return $this->sendResponse($people);
    }

    public function invitation()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $matchs = $this->matchService->getInvitationOfJoining();
        return $this->sendResponse($matchs);
    }
}
