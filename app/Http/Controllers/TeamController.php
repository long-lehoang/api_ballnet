<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\CaptainRequest;
use App\Http\Requests\Team\LocationRequest;
use App\Http\Requests\Team\OverviewRequest;
use App\Http\Requests\Team\KickRequest;
use App\Http\Requests\Team\AdminRequest;
use App\Http\Requests\Team\SportRequest;
use Illuminate\Support\Facades\Auth;
use App\Repository\TeamRepo;
use App\Contracts\Team;
use App\Contracts\Image;
use App\Models\MemberTeam;
use Log;

class TeamController extends Controller
{
    protected $team;    
    protected $teamService;
    protected $imageService;
    /**
     * __construct
     *
     * @param  mixed $team
     * @return void
     */
    function __construct(TeamRepo $team, Team $teamService, Image $imageService)
    {
        $this->teamService = $teamService;
        $this->team = $team;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $data = $this->teamService->getTeams();

        return $this->sendResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeamRequest $request)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //get current user
        $user = Auth::guard('api')->user();

        //get input
        $input = $request->all();
        $input["id_captain"] = $user->id;

        //store
        $team = $this->team->forceCreate($input);
        MemberTeam::create([
            'team_id' => $team->id,
            'member_id' => $user->id,
            'status' => 'active'
        ]);
        //response
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

        $result = $this->teamService->getTeam($id);
        return $this->sendResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeamRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //get input
        $input = $request->all();

        $team = $this->team->find($id);

        $this->authorize('captain', $team);

        $team->update($input);

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

        $team = $this->team->find($id);
        $this->authorize('captain',$team);
        $team->delete();

        return $this->sendResponse();
    }
    
    /**
     * myTeams
     *
     * @return void
     */
    public function myTeams()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->teamService->getMyTeam();

        return $this->sendResponse($team);
    }
    
    /**
     * leave
     *
     * @param  mixed $id
     * @return void
     */
    public function leave($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('member', $team);
        $result = $this->teamService->leave($id);

        if($result['success']){
            return $this->sendResponse(null, $result['message']);
        }else{
            return $this->sendError(null, $result['message']);
        }
    }
    
    /**
     * getPermission
     *
     * @param  mixed $id
     * @return void
     */
    public function getPermission($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $result = $this->teamService->getPermission($id);
        return $this->sendResponse($result);
    }
        
    /**
     * getAdmin
     *
     * @param  mixed $id
     * @return void
     */
    public function getAdmin($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('member',$team);
        $admin = $this->team->getAdmin($id);
        return $this->sendResponse($admin);
    }
    
    /**
     * setOverview
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setOverview(OverviewRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('captain',$team);

        $result = $this->team->update($id,$request->all());
        if($result === false){
            return $this->sendError();
        }else{
            return $this->sendResponse();
        }
    }
    
    /**
     * setLocation
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setLocation(LocationRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('captain',$team);

        $result = $this->team->update($id,$request->all());
        if($result === false){
            return $this->sendError();
        }else{
            return $this->sendResponse();
        }
    }
    
    /**
     * getMember
     *
     * @param  mixed $id
     * @return void
     */
    public function getMember($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('member',$team);
        $members = $this->team->getMembers($id);
        return $this->sendResponse($members);
    }
    
    /**
     * setAdmin
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setAdmin(Request $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('captain', $team);

        $admins = $request->admins;
        $admins = explode(',',$admins);
        $result = $this->teamService->setAdmin($id, $admins);
        
        if($result){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }
    
    /**
     * getPosts
     *
     * @param  mixed $id
     * @return void
     */
    public function getPosts($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('member', $team);
        $posts = $team->posts;
        return $this->sendResponse($posts);
    }
    
    /**
     * setAvatar
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setAvatar(Request $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('captain', $team);
        if($request->hasFile('image')){
            //upload image
            $img = $request->file('image');
            $result = $this->imageService->upload($img);
            if(!$result['success']){
                return $this->sendError(null, "Can't upload image");
            }
            //delete image
            $url = $team->avatar;
            if(!empty($url)){
                $this->imageService->delete($url);
            }
            //update DB
            $team->avatar = $result['url'];
            $team->save();
            return $this->sendResponse();
        }
        return $this->sendError(null, "Image Not Found");
    }
    
    /**
     * setCover
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setCover(Request $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('captain', $team);
        if($request->hasFile('image')){
            //upload image
            $img = $request->file('image');
            $result = $this->imageService->upload($img);
            if(!$result['success']){
                return $this->sendError(null, "Can't upload image");
            }
            //delete image
            $url = $team->cover;
            if(!empty($url)){
                $this->imageService->delete($url);
            }
            //update DB
            $team->cover = $result['url'];
            $team->save();
            return $this->sendResponse();
        }
        return $this->sendError(null, "Image Not Found");
    }
    
    /**
     * kickMember
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function kickMember(KickRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $memberId = $request->member_id;
        $team = $this->team->find($id);
        $this->authorize('kick', $team);

        $result = $this->teamService->kickMember($memberId);

        if($result){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }
    
    /**
     * getFriendToInvite
     *
     * @param  mixed $id
     * @return void
     */
    public function getFriendToInvite($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $team = $this->team->find($id);
        $this->authorize('member', $team);
        
        $result = $this->teamService->getFriendToInvite($id);
        return $this->sendResponse($result);
    }
    
    /**
     * setCaptain
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setCaptain(CaptainRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $captainId = $request->captain_id;
        $team = $this->team->find($id);
        $this->authorize('captain', $team);
        $result = $this->teamService->changeCaptain($id, $captainId);
        return $this->sendResponse();
    }
    
    /**
     * setSport
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function setSport(SportRequest $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //authorize
        $team = $this->team->find($id);
        $this->authorize('changeSport', $team);

        //get new sport
        $sport = $request->sport;

        //update
        $team->sport = $sport;
        $team->save();

        return $this->sendResponse();
    }
}
