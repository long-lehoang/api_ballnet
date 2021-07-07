<?php
namespace App\Repository;

use App\Repository\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class UserRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\User::class;
    }

    /**
     * Get current user
     * @return [json] user
     */
    public function getCurrentUser(){
        try{
            return $this->sendSuccess(Auth::guard('api')->user());
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

    /**
     * Check Login Valid
     * 
     * @param [json] [username,password]
     * @return [json] [user,access_token,token_type,expires_at]
     */
    public function isValidUser($credentials){
        if(Auth::guard('web')->attempt($credentials)){
            $user = Auth::guard('web')->user();
            //check lock user
            if($user->info->status === 'lock-account'){
                return $this->sendFailed();
            }
            
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            $responseData = [
                'user' => $user,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ];
            $expire_at = $tokenResult->token->expires_at;
            $minutes = $expire_at->diffInMinutes(Carbon::now());
            // setcookie('access_token',$tokenResult->accessToken,$minutes);
            return $this->sendSuccess($responseData);
        }else{
            return $this->sendFailed();
        }
    }

    /**
     * Revoke Token
     * 
     * @return bool
     */
    public function revokeToken(){
        try{
            $user = Auth::guard('api')->user();
            $user->token()->revoke();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    /**
     * Update Password
     * @param string new_password
     * 
     */
    public function updatePassword($request){
        $user = Auth::guard('api')->user();
        $user->password = bcrypt($request->new_password);
        $user->token()->revoke();
        $token = $user->createToken('Personal Access Token')->accessToken;
        if($user->save()){
            return $this->sendSuccess($token);
        }else{
            return $this->sendFailed($token);
        }
    }

    /**
     * Check By Password
     * 
     * @return bool
     */
    public function authByPassword($password){
        return Hash::check($password,Auth::guard('api')->user()->password);
    }

    /**
     * Get profile
     * 
     * @param string username
     * @return [json] profile
     */
    public function findUser($username)
    {
        try{
            $user = $this->_model::where("username", $username)->firstOrFail();
            return $this->sendSuccess($user);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

    public function deleteUser()
    {
        try{
            $user = Auth::guard('api')->user();

            $user->token()->revoke();
            $user->info()->delete();
            $user->delete();
            return $this->sendSuccess();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

    /**
     * Check username
     * 
     * @param string username
     * @return bool
     */
    public function checkUsername($username){
        try{
            $this->_model::where("username",$username)->firstOrFail();
            return $this->sendSuccess();
        }catch(Exception $e){   
            return $this->sendFailed();
        }
    }

    /**
     * Check email exists
     * 
     * @param string email
     * @return bool
     */
    public function checkEmail($email){
        try{
            $this->_model::where("email",$email)->firstOrFail();
            return $this->sendSuccess();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

}