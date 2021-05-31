<?php

namespace App\Policies;

use App\Models\FriendRequest;
use App\Models\User;
use App\Models\Friend;

class FriendPolicy
{

    public function acceptRequest(User $user, FriendRequest $request)
    {
        return $user->id === $request->user_id;
    }

    public function cancelRequest(User $user, FriendRequest $request)
    {
        return $user->id === $request->user_id || $user->id === $request->from_id;
    }

    public function friend(User $user, User $friend)
    {
        $result = Friend::where([
            ['user_id', $user->id],
            ['id_friend',$friend->id]
        ])->first();
        return !is_null($result)||$user->id==$friend->id;
    }
}
