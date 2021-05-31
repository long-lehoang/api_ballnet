<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Booking\ReviewStadium;
use App\Contracts\Booking;
use App\Repository\BookingRepo;
use App\Http\Requests\Booking\CreateRequest;

use Auth;
use Log;

class BookingController extends Controller
{

    protected $bookingService;
    protected $bookingRepo;

    function __construct(Booking $bookingService, BookingRepo $bookingRepo)
    {
        $this->bookingService = $bookingService;
        $this->bookingRepo = $bookingRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $this->bookingService->book($request->match_id, $request->stadium_id);

        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * getToReviewStadium
     *
     * @param  mixed $id
     * @return void
     */
    public function getToReview($id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //authorize
        $booking = $this->bookingRepo->find($id);
        $this->authorize('review', $booking);

        //handle
        $data = $this->bookingService->getToReview($id);

        return $this->sendResponse($data);
    }
     
        
    /**
     * reviewStadium
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function review(ReviewStadium $request, $id)
    {
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        //authorize
        $booking = $this->bookingRepo->find($id);
        $this->authorize('review', $booking);

        $this->bookingService->review($id, $request->rating, $request->feedback);

        return $this->sendResponse();
    }
}
