<?php
namespace App\Repository;

use App\Repository\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            $responseData = [
                'user' => $user,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()];
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
     * Update Profile
     * 
     * @return [json] message
     */
    public function updateProfile($request){
        $user = Auth::guard('api')->user();
        if(!empty($request->first_name)) $user->first_name = $request->first_name;
        if(!empty($request->last_name)) $user->last_name = $request->last_name;
        if(!empty($request->tel)) $user->tel = $request->tel;
        if(!empty($request->address)) $user->address = $request->address;
        if(!empty($request->email)) $user->email = $request->email;
        if($user->save()){
            return $this->sendSuccess();
        }else{
            return $this->sendFailed();
        }
    }

    /**
     * Get profile
     * 
     * @param id
     * @return [json] profile
     */
    public function getProfile($id)
    {
        $id = $id == null ? Auth::guard('api')->user()->id : $id ;

        $user = $this->find($id);

        if($user == null)
            return $this->sendFailed("Account Was Not Found ");

        $info = $user->info();

        dd($info);
        if($info == null){
            return $this->sendFailed("No Info");
        }else{
            return $this->sendSuccess($info);
        }
    }
}