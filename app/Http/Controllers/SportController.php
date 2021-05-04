<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\UserRepo;
use App\Repository\SportRepo;
use Auth;
use Log;

class SportController extends Controller
{
    /**
     * Constructor
     * 
     */
    protected $user;
    protected $sport;
    
    public function __construct(UserRepo $user, SportRepo $sport)
    {
        $this->user = $user;
        $this->sport = $sport;
    }

    /**
     * Show
     * 
     * @param string $username
     * @return [json]
     */
    public function show($username)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $user = $this->user->findUser($username);
        if($user['success']){
            return $this->sendResponse($user['data']->sports);
        }else{
            return $this->sendError();
        }
    }

    /**
     * Get main sport
     *
     */
    public function getMainSport($username)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $user = $this->user->findUser($username);
        if($user['success']){
            $mainSport = $this->sport->mainSport($user['data']->id);
            
            if($mainSport['success']){
                return $this->sendResponse($mainSport['data']);
            }else{
                return $this->sendError();
            }
        }else{
            return $this->sendError();
        }
    }
}