<?php
namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class ImagePostRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\ImagePost::class;
    }

    /**
     * Upload image
     * @param file $files
     * @param int $post_id
     * @return 
     */
    public function upload($post_id,$files){
        try{

            foreach($files as $file)
            {
                $fileName = uniqid().time(). '.' .$file->getClientOriginalExtension();  //Provide the file name with extension 
                $file->move(public_path().'/uploads/images/', $fileName);  
                $this->create([
                    "image" => '/uploads/images/'.$fileName,
                    "post_id" => $post_id
                ]);
            }
            return $this->sendSuccess();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}