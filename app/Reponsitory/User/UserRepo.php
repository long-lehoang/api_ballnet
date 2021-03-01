<?php
namespace App\Repository\Employee;

use App\Repository\Base\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeRepo extends BaseRepository implements IEmployeeRepo{
    public function setModel()
    {
        return \App\Models\Employee::class;
    }
    public function getCurrentUser(){
        try{
            return $this->sendSuccess(Auth::guard('employees-api')->user());
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
    public function isValidUser($credentials){
        if(Auth::guard('employees')->attempt($credentials)){
            $user = Auth::guard('employees')->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            $responseData = [
                'user' => $user
                ,'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()];
            return $this->sendSuccess($responseData);
        }else{
            return $this->sendFailed();
        }
    }
    public function revokeToken(){
        try{
            $user = Auth::guard('employees-api')->user();
            $user->token()->revoke();
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    public function updatePassword($request){
        $user = Auth::guard('employees-api')->user();
        $user->password = bcrypt($request->new_password);
        $user->token()->revoke();
        $token = $user->createToken('Personal Access Token')->accessToken;
        if($user->save()){
            return $this->sendSuccess($token);
        }else{
            return $this->sendFailed($token);
        }
    }
    public function authByPassword($password){
        return Hash::check($password,Auth::guard('employees-api')->user()->password);
    }
    public function updateProfile($request){
        $user = Auth::guard('employees-api')->user();
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
}