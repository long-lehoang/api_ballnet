<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Repository\RoomRepo;
use App\Repository\MessageRepo;
use App\Models\Room;
use Auth;

class MessageController extends Controller
{

    protected $roomRepo;
    protected $messageRepo;
    function __construct(RoomRepo $roomRepo, MessageRepo $messageRepo)
    {
        $this->roomRepo = $roomRepo;
        $this->messageRepo = $messageRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $messages = Room::find($id)->messages()->orderBy("updated_at", "desc")->paginate(config("constant.PAGINATION.MESSAGE.LIMIT"));
        return $this->sendResponse($messages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $room = $this->roomRepo->find($id);
        $message = $request->message;
        $this->messageRepo->forceCreate([
            "user_id" => Auth::id(),
            "message" => $message,
            "room_id" => $id
        ]);
        return $this->sendResponse();
    }
}
