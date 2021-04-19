<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\MemberTeam;
use App\Models\User;

class TeamPolicy
{

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

    public function cancel(User $user, MemberTeam $member)
    {
        if($member->invited_by !== null){
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

    public function approve(User $user, MemberTeam $member)
    {
        if($member->invited_by !== null){
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
}
