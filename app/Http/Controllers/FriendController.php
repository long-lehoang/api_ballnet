<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\FriendRepo;

class FriendController extends Controller
{
    //
    protected $repo;

    public function __construct(FriendRepo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Show list friends
     * 
     */
    public function index(Request $request)
    {
        $result = $this->repo->getFriends();
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }
}
