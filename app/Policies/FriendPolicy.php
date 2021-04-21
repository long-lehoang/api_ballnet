<?php

namespace App\Policies;

use App\Models\FriendRequest;
use App\Models\User;

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
}
