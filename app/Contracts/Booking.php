<?php

namespace App\Contracts;

interface Booking{    
            
    /**
     * getReviewStadium
     *
     * @param  mixed $id
     * @return void
     */
    public function getToReview($id);

    /**
     * reviewStadium
     *
     * @param  mixed $book_id
     * @param  mixed $rating
     * @param  mixed $feedback
     * @return void
     */
    public function review($book_id, $rating, $feedback);
    
    /**
     * book
     *
     * @param  mixed $matchId
     * @param  mixed $stadiumId
     * @return void
     */
    public function book($matchId, $stadiumId);
}