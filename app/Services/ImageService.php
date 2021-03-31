<?php

namespace App\Services;

use App\Contracts\Image;
use Illuminate\Support\Facades\File;
use Exception;

class ImageService implements Image{
    public function delete($url){
        if(File::exists(public_path().$url)){
            try{
                File::delete(public_path().$url);
                return true;  
            }catch(Exception $e){
                return false;
            }
        }else{
            return false;
        }
    }

    public function upload($file){
        try{
            $fileName = uniqid().time(). '.' .$file->getClientOriginalExtension();  //Provide the file name with extension 
            $file->move(public_path().'/uploads/images/', $fileName);
            return [
                "success" => true,
                "url" => '/uploads/images/'.$fileName
            ];
        }catch(Exception $e){
            return [
                "success"=> false,
                "url" => null
            ];
        }
    }
}