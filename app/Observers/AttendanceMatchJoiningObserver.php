<?php

namespace App\Observers;

use App\Models\AttendanceMatchJoining;
use App\Models\Sport;
use App\Models\MemberTeam;
use DB;

class AttendanceMatchJoiningObserver
{
    /**
     * Handle the AttendanceMatchJoining "created" event.
     *
     * @param  \App\Models\AttendanceMatchJoining  $attendanceMatchJoining
     * @return void
     */
    public function created(AttendanceMatchJoining $attendanceMatchJoining)
    {
        $joining = $attendanceMatchJoining->match_joining;
        //update rating
        $rating = DB::select("SELECT AVG(attendance_match_joining.rating) AS rating FROM attendance_match_joining 
        INNER JOIN match_joining ON attendance_match_joining.id_match_joining = match_joining.id 
        WHERE (match_joining.player_id = $joining->player_id)")[0];

        //update num match in team of user
        $numInTeam = DB::select("SELECT COUNT(*) AS num FROM attendance_match_joining 
        INNER JOIN match_joining ON attendance_match_joining.id_match_joining = match_joining.id 
        WHERE (match_joining.player_id = $joining->player_id) AND (match_joining.team_id = $joining->team_id) 
        GROUP BY match_joining.match_id
        HAVING (AVG(attendance) > 0.5)")[0];
        $member = MemberTeam::where([
            ['team_id', $joining->team_id],
            ['member_id', $joining->player_id]
        ])->first();
        if(!is_null($member)){
            $member->num_match = $numInTeam->num;
            $member->save();
        }

        //update num_match sport of user
        $numOfUser = DB::select("SELECT COUNT(*) AS num FROM attendance_match_joining 
        INNER JOIN match_joining ON attendance_match_joining.id_match_joining = match_joining.id 
        WHERE (match_joining.player_id = $joining->player_id)
        GROUP BY match_joining.match_id
        HAVING (AVG(attendance) > 0.5)")[0];
        
        Sport::updateOrCreate([
            'sport' => $joining->match->sport,
            'user_id' => $joining->player_id
        ],
        [
            'rating' => $rating->rating,
            'num_match' => $numOfUser->num
        ]);
    }

    /**
     * Handle the AttendanceMatchJoining "updated" event.
     *
     * @param  \App\Models\AttendanceMatchJoining  $attendanceMatchJoining
     * @return void
     */
    public function updated(AttendanceMatchJoining $attendanceMatchJoining)
    {
        //
    }

    /**
     * Handle the AttendanceMatchJoining "deleted" event.
     *
     * @param  \App\Models\AttendanceMatchJoining  $attendanceMatchJoining
     * @return void
     */
    public function deleted(AttendanceMatchJoining $attendanceMatchJoining)
    {
        //
    }

    /**
     * Handle the AttendanceMatchJoining "restored" event.
     *
     * @param  \App\Models\AttendanceMatchJoining  $attendanceMatchJoining
     * @return void
     */
    public function restored(AttendanceMatchJoining $attendanceMatchJoining)
    {
        //
    }

    /**
     * Handle the AttendanceMatchJoining "force deleted" event.
     *
     * @param  \App\Models\AttendanceMatchJoining  $attendanceMatchJoining
     * @return void
     */
    public function forceDeleted(AttendanceMatchJoining $attendanceMatchJoining)
    {
        //
    }
}
