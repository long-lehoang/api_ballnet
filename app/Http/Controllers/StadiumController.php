<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\StadiumRepo;

class StadiumController extends Controller
{
    protected $stdRepo;
    function __construct(StadiumRepo $stdRepo)
    {
        $this->stdRepo = $stdRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stadiums = $this->stdRepo->active();
        return $this->sendResponse($stadiums);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stadium = $this->stdRepo->find($id);
        return $this->sendResponse($stadium);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
