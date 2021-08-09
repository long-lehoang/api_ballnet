<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\MemberTeam;
use App\Models\User;

class TeamPolicy
{
    
    /**
     * invite
     *
     * @param  mixed $user
     * @param  mixed $team
     * @return void
     */
    public function invite(User $user, Team $team)
    {
        if($team->id_captain === $user->id){
            return true;
        }

        $admins = $team->admins;
        foreach ($admins as $key => $admin) {
            if($admin->admin_id === $user->id)
            {
                return true;
            }
        }
        return false;

    }
    
    /**
     * cancel
     *
     * @param  mixed $user
     * @param  mixed $member
     * @return void
     */
    public function cancel(User $user, MemberTeam $member)
    {
        if($member->invited_by !== null){
            $team = $member->team;
            if($user->id === $member->member_id){
                return true;
            }
            
            if($team->id_captain === $user->id){
                return true;
            }

            $admins = $team->admins;
            foreach ($admins as $key => $admin) {
                if($admin->admin_id === $user->id)
                {
                    return true;
                }
            }
            return false;
        }else{
            if($user->id === $member->member_id){
                return true;
            }
            else {
                return false;
            }
        }
        return false;
    }
    
    /**
     * approve
     *
     * @param  mixed $user
     * @param  mixed $member
     * @return void
     */
    public function approve(User $user, MemberTeam $member)
    {
        if($member->invited_by !== null){
            if($user->id === $member->member_id){
                return true;
            }
            else {
                return false;
            }
        }else{
            $team = $member->team;

            if($team->id_captain === $user->id){
                return true;
            }

            $admins = $team->admins;
            foreach ($admins as $key => $admin) {
                if($admin->admin_id === $user->id)
                {
                    return true;
                }
            }
            return false;
        }
    }
    
    /**
     * member
     *
     * @param  mixed $user
     * @param  mixed $team
     * @return void
     */
    public function member(User $user, Team $team)
    {
        return $team->members()->where([['status','active'],['member_id',$user->id]])->first() !== null;
    }
    
    /**
     * captain
     *
     * @param  mixed $user
     * @param  mixed $team
     * @return void
     */
    public function captain(User $user, Team $team)
    {
        return $team->id_captain === $user->id;
    }
    
    /**
     * kick
     *
     * @param  mixed $user
     * @param  mixed $team
     * @return void
     */
    public function kick(User $user, Team $team)
    {
        //is captain
        if($team->id_captain === $user->id){
            return true;
        }

        //user is admin
        $admin = $team->admins()->where('admin_id', $user->id)->first();
        if(is_null($admin)){
            return false;
        }
        return true;
    }
    
    /**
     * admin
     *
     * @param  mixed $user
     * @param  mixed $team
     * @return void
     */
    public function admin(User $user, Team $team)
    {
        //is captain
        if($team->id_captain === $user->id){
            return true;
        }

        //user is admin
        $admin = $team->admins()->where('admin_id', $user->id)->first();
        if(is_null($admin)){
            return false;
        }
        return true;
    }
    
    /**
     * changeSport
     *
     * @param  mixed $user
     * @param  mixed $team
     * @return void
     */
    public function changeSport(User $user, Team $team)
    {
        //is captain
        if($team->id_captain !== $user->id){
            return false;
        }

        //check exist match
        if(!empty($team->matchs->toArray())){
            return false;
        }

        return true;
    }
}
