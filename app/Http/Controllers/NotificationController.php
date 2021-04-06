<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class NotificationController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index()
    {
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
        try{
            $user = Auth::guard('api')->user();
            $user->unreadNotifications->markAsRead();
            return $this->sendResponse();
        }catch(Exception $e){
            return $this->sendError();
        }
    }
}
