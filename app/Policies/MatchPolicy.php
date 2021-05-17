<?php

namespace App\Policies;

use App\Models\Match;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\MatchInvitation;
use App\Models\MatchJoining;

class MatchPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Match  $match
     * @return mixed
     */
    public function view(User $user, Match $match)
    {
        if($match->private === 'Public'){
            return true;
        }

        $team1 = $match->team1;
        $team2 = $match->team2;
        if(!is_null($team1)){
            $member1 = $team1->members->filter(function($member){
                return $member->member_id === $user->id;
            });
            if(!empty($member1)){
                return true;
            }
        }
        if(!is_null($team2)){
            $member2 = $team2->members->filter(function($member){
                return $member->member_id === $user->id;
            });
            if(!empty($member2)){
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Match  $match
     * @return mixed
     */
    public function update(User $user, Match $match)
    {
        return $match->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Match  $match
     * @return mixed
     */
    public function delete(User $user, Match $match)
    {
        //check if captain
        return $match->team1->id_captain === $user->id;
    }

    public function leave(User $user, Match $match)
    {
        if(is_null($match->team2)){
            return false;
        }
        return $match->team2->id_captain === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Match  $match
     * @return mixed
     */
    public function restore(User $user, Match $match)
    {
        //
    }

    public function acceptTeam(User $user, MatchInvitation $invitation)
    {
        if($invitation->status == 'requested'){
            return $invitation->match->team1->id_captain === $user->id;
        }else{
            return $invitation->team->id_captain === $user->id;
        }
    }

    public function teamRequest(User $user, Match $match)
    {
        return is_null($match->team_2);
    }

    public function userJoin(User $user, Match $match)
    {
        if($match->private === 'Public'){
            return true;
        }

        if(!is_null($match->team1)&&!is_null($match->team1->members()->where('member_id', $user->id)->first())){
            return true;
        }

        if(!is_null($match->team2)&&!is_null($match->team2->members()->where('member_id', $user->id)->first())){
            return true;
        }

        return false;
    }

    public function updateJoining(User $user, MatchJoining $joining)
    {
        if($joining->status == 'invited' || $joining->status == 'suggested'){
            return $joining->player_id === $user->id;
        }else{
            return !is_null($joining->team->admins()->where("admin_id", $user->id)->first())||$joining->team->id_captain === $user->id;
        }
    }

    public function deleteJoining(User $user, MatchJoining $joining)
    {
        return $joining->player_id === $user->id||!is_null($joining->team->admins()->where("admin_id", $user->id)->first())||$joining->team->id_captain === $user->id;
    }

    public function review(User $user, Match $match)
    {
        //check finish
        if($match->status !== 'old'){
            return false;
        }

        //check user belong to match
        $joins = $match->joinings()->where('status', 'active')->pluck('player_id')->toArray();
        return in_array($user->id, $joins);
    }

}
