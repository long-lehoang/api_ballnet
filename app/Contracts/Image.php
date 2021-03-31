<?php

namespace App\Contracts;

interface Image{
    public function delete($url);
    public function upload($file);
}