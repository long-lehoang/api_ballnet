<?php

namespace App\Services;

use App\Contracts\Booking;
use App\Repository\BookingRepo;

class BookingService implements Booking{

    protected $bookingRepo;

    function __construct(BookingRepo $bookingRepo){
        $this->bookingRepo = $bookingRepo;
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
}