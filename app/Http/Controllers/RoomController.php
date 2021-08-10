<?php

namespace App\Http\Controllers;

use App\Http\Requests\Room\CreateRequest;
use App\Http\Requests\Room\UpdateRequest;
use App\Repository\RoomRepo;
use Auth;

class RoomController extends Controller
{
    //
    protected $roomRepo;
    function __construct(RoomRepo $roomRepo)
    {
        $this->roomRepo = $roomRepo;
    }

    public function index()
    {
        $rooms = $this->roomRepo->all();
        $rooms = empty($rooms->toArray()) ? [] : $rooms->toQuery()->orderBy('updated_at', 'desc')->get();
        return $this->sendResponse($rooms);
    }

    public function show($id)
    {
        $room = $this->roomRepo->find($id);
        return $this->sendResponse($room);
    }

    public function store(CreateRequest $request)
    {
        $members = $request->members;
        $members = explode(",",$members);
        $room = $this->roomRepo->forceCreate($members);
        return $this->sendResponse($room);
    }

    public function getParty($id)
    {
        $room = $this->roomRepo->find($id);
        $party = $room->users;
        if($party[0]->user_id != Auth::id()){
            $party = $party[0];
        }else if(count($party)>1){
            $party = $party[1];
        }else{
            $party = null;
        }
        $result = new \stdClass;
        if(is_null($party)){
            $result->id = '';
            $result->name = 'No Name';
            $result->avatar = null;
            $result->username = '';
            $result->status = 0;
        }else{
            $result->id = $party->user_id;
            $result->name = $party->user->name;
            $result->avatar = $party->user->info->avatar;
            $result->username = $party->user->username;
            $result->status = 0;
        }
        return $this->sendResponse($result);
        //TODO: not support for group
    }

    // public function update(UpdateRequest $request, $id);
    // {
    //     $this->roomRepo->update($id, $request->all());
    //     return $this->sendResponse();
    // }


}
