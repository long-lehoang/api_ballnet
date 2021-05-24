<?php

namespace App\Services;

use App\Contracts\Booking;
use App\Repository\BookingRepo;
use App\Repository\MatchRepo;
use App\Repository\StadiumRepo;
use Auth;

class BookingService implements Booking{

    protected $bookingRepo;
    protected $matchRepo;
    protected $stdRepo;

    function __construct(BookingRepo $bookingRepo, MatchRepo $matchRepo, StadiumRepo $stdRepo){
        $this->bookingRepo = $bookingRepo;
        $this->matchRepo = $matchRepo;
        $this->stdRepo = $stdRepo;
    }

    /**
     * getReviewStadium
     *
     * @param  mixed $id
     * @return void
     */
    public function getToReview($id)
    {
        $booking = $this->bookingRepo->find($id);

        $stadium = $booking->stadium;

        $data = new \stdClass;
        $data->id = $stadium->id;
        $data->name = $stadium->name;

        return $data;
    }
    
    /**
     * reviewStadium
     *
     * @param  mixed $book_id
     * @param  mixed $rating
     * @param  mixed $feedback
     * @return void
     */
    public function review($book_id, $rating, $feedback)
    {
        $book = $this->bookingRepo->find($book_id);
        $book->rating = $rating;
        $book->feedback = $feedback;
        $book->save();
    }
    
    /**
     * book
     *
     * @param  mixed $matchId
     * @param  mixed $stadiumId
     * @return void
     */
    public function book($matchId, $stadiumId)
    {
        $match = $this->matchRepo->find($matchId);
        //delete before booking with match
        $this->bookingRepo->deleteByMatch($matchId);

        //create new booking
        $this->bookingRepo->create([
            'stadium_id' => $stadiumId,
            'user_id' => Auth::id(),
            'booking_time' => $match->time,
            'match_id' => $matchId,
        ]);
        
        //update new location for match
        $stadium = $this->stdRepo->find($stadiumId);
        $location = explode(', ', $stadium->location);

        $match->location = $location[2].", ".$location[3];
        $match->save();
    }

}