<?php

namespace App\Observers;

use App\Models\AttendanceMatchJoining;
use App\Models\Sport;
use App\Models\MemberTeam;
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
        //update num_match
        $attendants = AttendanceMatchJoining::where('id_match_joining', $attendanceMatchJoining->id_match_joining)->get();
        
        $attendances = $attendants->pluck('attendance')->toArray();
        $attendances = array_count_values($attendances);
        $attendance = array_keys($attendances, max($attendances));

        $rating = $attendants->pluck('rating')->toArray();
        $rating = array_sum($rating)/count($rating);
        if($attendance == 1){
            //update num_match in team
            $member = MemberTeam::where([
                ['team_id', $joining->team_id],
                ['member_id', $joining->player_id]
            ])->first();
            if(!is_null($member)){
                $member->num_match++;
                $member->save();
            }
            //update num_match sport of user
            
            $sport = Sport::where([
                ['sport', $joining->match->sport],
                ['user_id', $joining->player_id]
            ])->first();
    
            if(is_null($sport)){
                Sport::create([
                    'sport' => $joining->match->sport,
                    'user_id' => $joining->player_id,
                    'rating' => $rating,
                    'num_match' => 1
                ]);
            }else{
                $sport = Sport::where([
                    'sport' => $joining->match->sport,
                    'user_id' => $joining->player_id,
                ]);

                $sport->rating = ($sport->rating * $sport->num_match + $rating)/($sport->num_match + 1)
                $sport->num_match++;
                $sport->save();
            }
        }
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
