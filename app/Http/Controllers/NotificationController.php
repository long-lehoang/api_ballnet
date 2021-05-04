<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        try{
            $user = Auth::guard('api')->user();
            $notification = $user->notifications()->get()->toArray();
            return $this->sendResponse($notification);
        }catch(Exception $e){
            return $this->sendError();
        }
    }

    public function readAll()
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        try{
            $user = Auth::guard('api')->user();
            $user->unreadNotifications->markAsRead();
            return $this->sendResponse();
        }catch(Exception $e){
            return $this->sendError();
        }
    }

    public function delete($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        try{
            $user = Auth::guard('api')->user();
            $user->notifications()->findOrFail($id)->delete();
            return $this->sendResponse();
        }catch(Exception $e){
            return $this->sendError();
        }
    }
}
