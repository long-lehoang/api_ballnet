<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest as LoginApiRequest;
use App\Http\Requests\Auth\ChangePasswordRequest as ChangePasswordApiRequest;
use App\Http\Requests\Auth\EditEmployeeRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Repository\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function __construct(UserRepo $repo)
    {
        $this->repo = $repo;
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

    /**
     * Signup user 
     * 
     * @return [json] data
     */
    public function signup(SignupRequest $request){
        $user = $request->except('c_password');
        $user['password'] = Hash::make($user['password']);
        
        $result = $this->repo->create($user);
        if ($result) {
            return $this->sendResponse(null,AUTHENTICATION::SIGNUP['SUCCESS']);
        }else {
            return $this->sendError(null,AUTHENTICATION::SIGNUP['FAILED']);
        }

    }
}
