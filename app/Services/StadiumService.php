<?php

namespace App\Services;

use App\Contracts\Stadium;
use App\Repository\StadiumRepo;
use App\Repository\ExtensionStadiumRepo;

class StadiumService implements Stadium{

    protected $stdRepo;
    protected $extRepo;

    function __construct(StadiumRepo $stdRepo, ExtensionStadiumRepo $extRepo){
        $this->stdRepo = $stdRepo;
        $this->extRepo = $extRepo;
    }
    
    /**
     * setExtension
     *
     * @param  mixed $id
     * @param  mixed $extensions
     * @return void
     */
    public function setExtension($id, $extensions){
        $extensions = explode(',', $extensions);
        foreach ($extensions as $ext) {
            $this->extRepo->create([
                'stadium_id' => $id,
                'extension' => trim($ext)
            ]);
        }
    }
    
}