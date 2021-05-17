<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Match\CreateMatchRequest;
use App\Http\Requests\Match\UpdateMatchRequest;
use App\Http\Requests\Match\InviteRequest;
use Illuminate\Support\Facades\Auth;
use App\Repository\MatchRepo;
use App\Repository\MatchJoiningRepo;
use App\Repository\TeamRepo;
use App\Contracts\Match;
use Log;

class MatchController extends Controller
{
    protected $matchRepo;
    protected $matchJoiningRepo;
    protected $teamRepo;
    protected $matchService;

    function __construct(Match $matchService, MatchRepo $match, MatchJoiningRepo $matchJoining, TeamRepo $team)
    {
        $this->matchRepo = $match;
        $this->matchJoiningRepo = $matchJoining;
        $this->teamRepo = $team;
        $this->matchService = $matchService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //get data
        $data = $this->matchRepo->all();
        
        //response
        return $this->sendResponse($data);
    }
    
    /**
     * invitation
     *
     * @return void
     */
    public function invitation()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //get data
        $data = $this->matchJoiningRepo->invitation();
        
        //response
        return $this->sendResponse($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMatchRequest $request)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //get input
        $input = $request->all();
        $teamId = $input['team_1'];
        $team = $this->teamRepo->find($teamId);
        $this->authorize('captain', $team);

        $input["created_by"] = Auth::id();
        if($input['private'] === 'Team'){
            $input['team_2'] = $input['team_1'];
        }
        //save input
        $this->matchRepo->forceCreate($input);
        return $this->sendResponse();
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

        //authorize
        $match = $this->matchRepo->find($id);
        $this->authorize('view', $match);
        return $this->sendResponse($match);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMatchRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //authorize
        $match = $this->matchRepo->find($id);

        $this->authorize('update', $match);
        
        //get params
        $params = $request->all();
        Log::debug("Data Input: [".json_encode($params)."]");

        //update
        $match->update($params);

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

        //authorize
        $match = $this->matchRepo->find($id);

        $this->authorize('delete', $match);

        $match->delete();

        return $this->sendResponse();

    }
    
    /**
     * leave
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function leave($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $match = $this->matchRepo->find($id);
        $this->authorize('leave', $match);

        $match->team_2 = null;
        
        $match->save();

        return $this->sendResponse();
    }

    public function invite(InviteRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $match = $this->matchRepo->find($id);
        $this->authorize('member', $match->team1);

        $team_id = $request->team_id;
        $this->matchService->inviteTeam($team_id, $id);
        return $this->sendResponse();
    }
    
    /**
     * memberOfTeam
     *
     * @param  mixed $id
     * @param  mixed $team_id
     * @return void
     */
    public function memberOfTeam($id, $team_id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $members = $this->matchService->memberOfTeam($id, $team_id);

        return $this->sendResponse($members);
    }
    
    /**
     * removeTeam
     *
     * @param  mixed $id
     * @return void
     */
    public function removeTeam($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $match = $this->matchRepo->find($id);
        $this->authorize('captain', $match->team1);

        $match->team_2 = null;
        $match->save();

        return $this->sendResponse();
    }
    
    /**
     * getTeamRequestOfMatch
     *
     * @param  mixed $id
     * @return void
     */
    public function getTeamRequestOfMatch($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $match = $this->matchRepo->find($id);
        $this->authorize('captain', $match->team1);
        
        $invitations = $this->matchService->getTeamRequestOfMatch($id);
        return $this->sendResponse($invitations);
    }
    
    /**
     * requestOfTeam
     *
     * @param  mixed $id
     * @param  mixed $team_id
     * @return void
     */
    public function requestOfTeam($id, $team_id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->teamRepo->find($team_id);
        $this->authorize('admin', $team);

        $requests = $this->matchService->requestOfTeam($id, $team_id);

        return $this->sendResponse($requests);
    }
    
    /**
     * getToReviewMember
     *
     * @param  mixed $id
     * @return void
     */
    public function getToReviewMember($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //authorize
        $match = $this->matchRepo->find($id);
        $this->authorize('reviewMember', $match);

        //handle
        $data = $this->matchService->getReviewMember($id);

        return $this->sendResponse($data);
    }
    
    /**
     * reviewMember
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function reviewMember(Request $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //authorize
        $match = $this->matchRepo->find($id);
        $this->authorize('reviewMember', $match);

        $this->matchService->reviewMember($request->result,$request->match_id, $request->team_id, $request->rating_team, $request->members);

        return $this->sendResponse();
    }
    
    /**
     * getToReviewStadium
     *
     * @param  mixed $id
     * @return void
     */
    public function getToReviewStadium($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //authorize
        $match = $this->matchRepo->find($id);
        $this->authorize('reviewStadium', $match);

        //handle
        $data = $this->matchService->getReviewStadium($id);

        return $this->sendResponse($data);
    }
    
    /**
     * reviewStadium
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function reviewStadium(Request $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //TODO

        return $this->sendResponse();
    }
}
