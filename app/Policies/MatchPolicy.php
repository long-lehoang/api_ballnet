<?php

namespace App\Policies;

use App\Models\Match;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        $joining = $match->joinings->filter(function($join){
            return $join->status === 'active';
        });
        if(!empty($joining))
        {
            return false;
        }

        //check if admin
        $admins = $match->team1->admins->filter(function($admin){
            return $admin->admin_id === $user->id;   
        });

        //check if captain
        $captain = $match->team1->id_captain === $user->id;
        
        return $captain||$admins;
    }

    public function leave(User $user, Match $match)
    {
        //check if admin
        $admins = $match->team1->admins->filter(function($admin){
            return $admin->admin_id === $user->id;   
        });

        //check if captain
        $captain = $match->team1->id_captain === $user->id;
        if(!$captain&&!$admins){
            return false;
        }

        //check if have member join match        
        $joins = $match->joinings->filter(function($join){
            return $join->team_id===$join->match->$team_2;
        });
        if(!empty($joins)){
            return false;
        }

        return true;
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

    public function acceptTeam(User $user, Match $match)
    {
        //TODO
    }
}
