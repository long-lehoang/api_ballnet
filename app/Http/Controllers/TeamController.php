<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use Illuminate\Support\Facades\Auth;
use App\Repository\TeamRepo;
use App\Contracts\Team;

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
        $data = $this->team->all();

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
        $this->team->forceCreate($input);

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

    public function myTeams()
    {
        $team = $this->teamService->getMyTeam();

        return $this->sendResponse($team);
    }
}
