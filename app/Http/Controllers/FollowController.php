<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\FollowRepo;

class FollowController extends Controller
{
    //
    protected $repo;

    public function __construct(FollowRepo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Count friends
     * 
     * @return [json]
     */
    public function count($id){
        $result = $this->repo->count($id);
        if($result['success']){
            return $this->sendResponse($result['data']);
        }
        else{
            return $this->sendError();
        }
    }
}