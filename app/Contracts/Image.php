<?php

namespace App\Contracts;

interface Image{    
    /**
     * delete
     *
     * @param  mixed $url
     * @return void
     */
    public function delete($url);    
    /**
     * upload
     *
     * @param  mixed $file
     * @return void
     */
    public function upload($file);
}