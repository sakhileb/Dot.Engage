<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VideoSession;

class VideoSessionPolicy
{
    /**
     * Any team member may browse sessions belonging to their team.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * A user may view a session only if they belong to the same team.
     */
    public function view(User $user, VideoSession $videoSession): bool
    {
        return $user->belongsToTeam($videoSession->team);
    }

    /**
     * Any team member may initiate a new video session.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the session initiator or a team admin may update session details.
     */
    public function update(User $user, VideoSession $videoSession): bool
    {
        return $user->belongsToTeam($videoSession->team)
            && ($videoSession->initiated_by === $user->id
                || $user->hasTeamRole($videoSession->team, 'admin'));
    }

    /**
     * Only the initiator or a team admin may end / delete a session.
     */
    public function delete(User $user, VideoSession $videoSession): bool
    {
        return $user->belongsToTeam($videoSession->team)
            && ($videoSession->initiated_by === $user->id
                || $user->hasTeamRole($videoSession->team, 'admin'));
    }

    /**
     * Only team admins may restore a session record.
     */
    public function restore(User $user, VideoSession $videoSession): bool
    {
        return $user->belongsToTeam($videoSession->team)
            && $user->hasTeamRole($videoSession->team, 'admin');
    }

    /**
     * Only team admins may permanently delete a session record.
     */
    public function forceDelete(User $user, VideoSession $videoSession): bool
    {
        return $user->belongsToTeam($videoSession->team)
            && $user->hasTeamRole($videoSession->team, 'admin');
    }

    /**
     * Any team member may join an active session.
     */
    public function join(User $user, VideoSession $videoSession): bool
    {
        return $user->belongsToTeam($videoSession->team)
            && $videoSession->status === 'active';
    }

    /**
     * A participant may capture a signature during an active session.
     */
    public function sign(User $user, VideoSession $videoSession): bool
    {
        return $user->belongsToTeam($videoSession->team)
            && $videoSession->status === 'active'
            && $videoSession->contract_id !== null;
    }
}
