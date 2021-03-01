<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest as LoginApiRequest;
use App\Http\Requests\Auth\ChangePasswordRequest as ChangePasswordApiRequest;
use App\Http\Requests\Auth\EditEmployeeRequest;
use App\Repository\User\IUserRepo;
use Illuminate\Http\Request;

abstract class AUTHENTICATION
{
    const UPDATE_PASSWORD = [
        'SUCCESS' => '',
        'FAILED' => '',
        'WRONG_CURRENT_PASSWORD' => 'Current password is incorrect'
    ];
    const LOGIN = [
        'SUCCESS' => '',
        'FAILED' => '',
        'INCORRECT' => 'Username or password is incorrect'
    ];
}
class AuthController extends BaseApiController
{
    protected $repo;
    
    public function __construct(IUserRepo $repo)
    {
        $this->repo = $repo;
    }
    public function login(LoginApiRequest $request){
        $credentials = $request->only('username','password');
        $result = $this->repo->isValidUser($credentials);
        if ($result['success']) {
            return $this->sendResponse($result['data']);
        }else {
            //Login Failed
            return $this->sendError(null,AUTHENTICATION::LOGIN['INCORRECT']);
        }
    }

    /**
     * Update Employee Profile
     *
     * @return [json] message
     */
    function updateProfile(EditEmployeeRequest $request) {
        $result = $this->repo->updateProfile($request);
        if($result['success']){
            return $this->sendResponse();
        }
        return $this -> sendError();
    }

     /**
     * Change Password
     *
     * @return [json] message
     */
    function changePassword(ChangePasswordApiRequest $request) {
        if($this->repo->authByPassword($request->current_password)){
            $result = $this->repo->updatePassword($request);
            if($result['success']){
                return $this->sendResponse(['new_token'=>$result['data']]);
            }
            return $this -> sendError();
        }else{
            return $this -> sendError(null,AUTHENTICATION::UPDATE_PASSWORD['WRONG_CURRENT_PASSWORD']);
        }
   }

    /**
     * Get Profile
     *
     * @return [json] message
     */
    function getProfile(Request $request) {
        $result = $this->repo->getCurrentUser();
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }

     /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        if($this->repo->revokeToken()){
            return $this -> sendResponse(null,'Success');
        }else{
            return $this -> sendError(null,"Failed");
        }
    }
}
