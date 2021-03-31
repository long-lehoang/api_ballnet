<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\UserRepo;
use App\Repository\InfoRepo;
use App\Http\Requests\Profile\LocationRequest;
use App\Http\Requests\Profile\PhoneRequest;
use App\Http\Requests\Profile\EmailRequest;
use App\Http\Requests\Profile\BirthdayRequest;
use App\Http\Requests\Profile\OverviewRequest;
use App\Http\Requests\Profile\NameRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Profile\UsernameRequest;
use Exception;

class ProfileController extends Controller
{
    protected $repo;
    protected $infoRepo;

    public function __construct(UserRepo $repo, InfoRepo $infoRepo)
    {
        $this->repo = $repo;
        $this->infoRepo = $infoRepo;
    }
    
    /**
     * Get profile
     * 
     * @return [json]
     */
    public function show($username){
        $user = $this->repo->findUser($username);
        if($user['success']){
            return $this->sendResponse($user['data']->info);
        }else{
            return $this->sendError();
        }
    }
    /**
     * Update User
     * 
     * @param 
     * @return [json] message
     */
    private function updateUser($param)
    {
        $user = Auth::guard('api')->user();
        try{
            $result = $this->repo->update($user->id, $param);
        }catch(Exception $e){
            return $this->sendError();
        }
        if(!$result){
            return $this->sendError();
        }

        return $this->sendResponse($result);
    }
    
    /**
     * Update Profile
     * 
     * @param 
     * @return [json] message
     */
    private function updateProfile($param)
    {
        $user = Auth::guard('api')->user();
        try{
            $result = $this->infoRepo->update($user->id, $param);
        }catch(Exception $e){
            return $this->sendError();
        }
        if(!$result){
            return $this->sendError();
        }

        return $this->sendResponse($result);
    }

    /**
     * Update location
     * 
     * @param 
     * @return [json] message
     */
    public function updateAddress(LocationRequest $request)
    {
        return $this->updateProfile($request->all());
    }
    
    /**
     * Update name
     * 
     * @param 
     * @return [json] message
     */
    public function updateName(NameRequest $request)
    {
        return $this->updateUser($request->all());
    }
    
    /**
     * Update username
     * 
     * @param 
     * @return [json] message
     */
    public function updateUsername(UsernameRequest $request)
    {
        return $this->updateUser($request->all());
    }
    /**
     * Update email
     * 
     * @param 
     * @return [json] message
     */
    public function updateEmail(EmailRequest $request)
    {
        return $this->updateUser($request->all());
    }

    /**
     * Update overview
     * 
     * @param 
     * @return [json] message
     */
    public function updateOverview(OverviewRequest $request)
    {
        return $this->updateProfile($request->all());
    }

    /**
     * Update phone
     * 
     * @param 
     * @return [json] message
     */
    public function updatePhone(PhoneRequest $request)
    {
        return $this->updateProfile($request->all());
    }

    /**
     * Update birthday
     * 
     * @param 
     * @return [json] message
     */
    public function updateBirthday(BirthdayRequest $request)
    {
        return $this->updateProfile($request->all());
    }
}