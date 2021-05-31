<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Exception;


class AdminTeamRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\AdminTeam::class;
    }

    public function deleteAdmin($teamId, $adminId)
    {
        $admin = $this->_model::where([
            ['team_id', $teamId],
            ['admin_id',$adminId]
        ])->delete();
    }
}