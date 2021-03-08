<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    public function show($path)
    {
        $pathToFile = public_path().'/uploads/images/'.$path;
        return response()->download($pathToFile);
    }
}
