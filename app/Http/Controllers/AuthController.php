<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest as LoginApiRequest;
use App\Http\Requests\Auth\ChangePasswordRequest as ChangePasswordApiRequest;
use App\Http\Requests\Auth\EditProfileRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\DeleteRequest;
use App\Repository\UserRepo;
use App\Repository\InfoRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
    const SIGNUP = [
        'SUCCESS' => 'Register is successful',
        'FAILED' => 'Signup is failure'
    ];
    const DELETE = [
        'FAILED' => 'Password or email is incorrect'
    ];
}

/**
 * @OA\Post(
 * path="/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *       @OA\Property(property="persistent", type="boolean", example="true"),
 *    ),
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
 *        )
 *     )
 * )
 */

class AuthController extends Controller
{
    protected $repo;
    protected $infoRepo;

    public function __construct(UserRepo $repo, InfoRepo $infoRepo)
    {
        $this->repo = $repo;
        $this->infoRepo = $infoRepo;
    }

    /**
     * Login function
     * 
     * @return [json] data
     */
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
     * Update User Profile
     *
     * @return [json] message
     */
    function updateProfile(EditProfileRequest $request) {
        $result = $this->repo->updateProfile($request);
        if($result['success']){
            return $this->sendResponse($result['data']);
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

    /**
     * Signup user 
     * 
     * @return [json] data
     */
    public function signup(SignupRequest $request){
        $user = $request->except('c_password');
        $user['password'] = Hash::make($user['password']);
        
        $result = $this->repo->create($user);

        try{
            $info = $this->infoRepo->create([
                "user_id"=>$result->id,
                "sex" => "male",
                "phone" => ""
            ]);
        }catch(Exception $e){
            return $this->sendError();
        }
        
        if ($result) {
            return $this->sendResponse(null,AUTHENTICATION::SIGNUP['SUCCESS']);
        }else {
            return $this->sendError(null,AUTHENTICATION::SIGNUP['FAILED']);
        }

    }

    /**
     * Delete account
     * 
     * @return [json] message
     */
    public function delete(DeleteRequest $request){
        if(Auth::guard('api')->user()->email != $request->email){
            return $this->sendError(null,AUTHENTICATION::DELETE['FAILED']);
        }
        if($this->repo->authByPassword($request->password)){
            $result = $this->repo->deleteUser();
            if($result['success']){
                $this->sendResponse();
            }else{
                $this->sendError();
            }
        }else{
            return $this->sendError(null,AUTHENTICATION::DELETE['FAILED']);
        }
    }

    /**
     * Get profile
     * 
     * @return [json]
     */
    public function getProfile($id){
        $user = $this->repo->find($id);
        $profile = $user->info;
        if($profile){
            return $this->sendResponse($profile);
        }else{
            return $this->sendError();
        }
    }

    /**
     * Check username
     * 
     * @return [json]
     */
    public function checkUsername($username){
        $result = $this->repo->checkUsername($username);
        if(!$result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }
}