<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\People;
use Auth;
use Log;

class PeopleController extends Controller
{
    protected $peopleService;

    public function __construct(People $peopleService)
    {
        $this->peopleService = $peopleService;
    }
    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $result = $this->peopleService->getPeople();

        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError(null, $result['message'], 500);
        }
    }
}
