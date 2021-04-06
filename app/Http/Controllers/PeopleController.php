<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\People;

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
        $result = $this->peopleService->getPeople();

        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError(null, $result['message'], 500);
        }
    }
}
