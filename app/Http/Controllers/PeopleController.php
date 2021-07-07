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

    
    public function search(Request $request)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");
        $key = $request->search;
        $city = $request->city;
        $district = $request->district;
        $sport = $request->sport;
        Log::debug("Query: key=$key, city=$city, district=$district, sport=$sport");
        
        $location = '';
        if(!empty($district)||!empty($city))
        $location = "$district, $city";
        
        $data = $this->peopleService->search($key, $location, $sport);
        return $this->sendResponse($data);
    }
}
