<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\LocationRequest;
use App\Http\Requests\Team\OverviewRequest;
use Illuminate\Support\Facades\Auth;
use App\Repository\TeamRepo;
use App\Contracts\Team;
use App\Models\MemberTeam;

class TeamController extends Controller
{
    protected $team;    
    protected $teamService;
    /**
     * __construct
     *
     * @param  mixed $team
     * @return void
     */
    function __construct(TeamRepo $team, Team $teamService)
    {
        $this->teamService = $teamService;
        $this->team = $team;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $team = $this->team->find($id);

        return $this->sendResponse($team);
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
        //get input
        $input = $request->all();

        $team = $this->team->find($id);

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
        $team = $this->team->find($id);

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
        $result = $this->teamService->leave($id);

        if($result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError();
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
        $result = $this->teamService->getPermission($id);
        return $this->sendResponse($result);
    }
    
    public function getAdmin($id)
    {
        return $this->sendResponse();
    }

    public function setOverview(OverviewRequest $request, $id)
    {
        $result = $this->teamRepo->update($id,$request->all());
        if($result === false){
            return $this->sendError();
        }else{
            return $this->sendResponse();
        }
    }

    public function setLocation(LocationRequest $request, $id)
    {
        $result = $this->teamRepo->update($id,$request->all());
        if($result === false){
            return $this->sendError();
        }else{
            return $this->sendResponse();
        }
    }
}
