<?php
namespace App\Repository;

use Exception;
use App\Repository\BaseRepository;
use Auth;
use App\Models\UserRoom;
use App\Models\User;

class RoomRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Room::class;
    }

    public function find($id)
    {
        $room = $this->_model::findOrFail($id);
        return $room;
    }

    public function all()
    {
        $rooms = Auth::guard('api')->user()->rooms->map->room;
        return $rooms;
    }

    protected function _checkExistRoom($friend)
    {
        $myRoom = Auth::guard('api')->user()->rooms->filter(function($room){
            return count($room->room->users) == 2;
        })->pluck('room_id')->all();
        $friendRoom = User::find($friend)->rooms->filter(function($room){
            return count($room->room->users) == 2;
        })->pluck('room_id')->all();
        return array_intersect($myRoom, $friendRoom);
    }

    public function forceCreate($params)
    {
        $room = null;
        if(count($params)>1){
            //case group chat
        }else{
            $ids = $this->_checkExistRoom($params[0]);
            if(count($ids)>0){
                $ids = array_values($ids)[0];
                $room = $this->find($ids);
            }else{
                $room = $this->_model->Create([
                    "name" => "Friend Chat"
                ]);
            }
        }

        UserRoom::firstOrCreate([
            "room_id" => $room->id,
            "user_id" => Auth::id()
        ]);

        foreach ($params as $key => $value) {
            UserRoom::firstOrCreate([
                "room_id" => $room->id,
                "user_id" => $value
            ]);
        }

        return $room;
    }
}