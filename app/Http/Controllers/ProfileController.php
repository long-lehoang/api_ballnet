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
use App\Contracts\Image;
use Exception;

class ProfileController extends Controller
{
    protected $userRepo;
    protected $infoRepo;
    protected $imageService;
    public function __construct(UserRepo $userRepo, InfoRepo $infoRepo, Image $imageService)
    {
        $this->userRepo = $userRepo;
        $this->infoRepo = $infoRepo;
        $this->imageService = $imageService;
    }
    
    /**
     * Get profile
     * 
     * @return [json]
     */
    public function show($username){
        $user = $this->userRepo->findUser($username);
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
            $result = $this->userRepo->update($user->id, $param);
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

    /**
     * Update avatar
     * 
     */
    public function updateAvatar(Request $request)
    {
        if($request->hasFile('image')){
            $user = Auth::guard('api')->user();
            $profile = $user->info;
            //upload image
            $img = $request->file('image');
            $result = $this->imageService->upload($img);
            if(!$result['success']){
                return $this->sendError(null, "Can't upload image");
            }
            //delete image
            $url = $profile->avatar;
            if(!empty($url)){
                $this->imageService->delete($url);
            }
            //update DB
            $profile->avatar = $result['url'];
            $profile->save();
            return $this->sendResponse($profile);
        }
        return $this->sendError(null, "Image Not Found");
    }

    /**
     * Update cover
     * 
     * 
     */
    public function updateCover(Request $request)
    {                
        if($request->hasFile('image')){
            $user = Auth::guard('api')->user();
            $profile = $user->info;
            // upload image
            $img = $request->file('image');
            $result = $this->imageService->upload($img);
            if(!$result['success']){
                return $this->sendError(null, "Can't upload image");
            }
            //delete image
            $url = $profile->cover;
            if(!empty($url)){
                $this->imageService->delete($url);
            }
            //update DB
            $profile->cover = $result['url'];
            $profile->save();
            return $this->sendResponse($profile);
        }
        return $this->sendError(null, "Image Not Found");
    }
    
}