<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Log;
use App\Contracts\Suggestion;

class SuggestionController extends Controller
{
    protected $suggestService;

    function __construct(Suggestion $suggestService)
    {
        $this->suggestService = $suggestService;
    }

    public function friend()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");
        $friends = $this->suggestService->friend();

        return $this->sendResponse($friends);
    }

    public function match()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $matchs = $this->suggestService->match();

        return $this->sendResponse($matchs);
    }

    public function stadium()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $stadiums = $this->suggestService->stadium();

        return $this->sendResponse($stadiums);
    }

}
