<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    /**
     * Any team member may list contracts belonging to their current team.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * A user may view a contract when they belong to the same team.
     */
    public function view(User $user, Contract $contract): bool
    {
        return $user->belongsToTeam($contract->team);
    }

    /**
     * Any authenticated team member may create a contract.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the creator or a team admin may edit a contract.
     * Signed contracts are immutable for everyone.
     */
    public function update(User $user, Contract $contract): bool
    {
        if ($contract->status === 'signed') {
            return false;
        }

        return $user->belongsToTeam($contract->team)
            && ($contract->created_by === $user->id
                || $user->hasTeamRole($contract->team, 'admin'));
    }

    /**
     * Only the creator or a team admin may soft-delete a contract.
     */
    public function delete(User $user, Contract $contract): bool
    {
        return $user->belongsToTeam($contract->team)
            && ($contract->created_by === $user->id
                || $user->hasTeamRole($contract->team, 'admin'));
    }

    /**
     * Only a team admin may restore a soft-deleted contract.
     */
    public function restore(User $user, Contract $contract): bool
    {
        return $user->belongsToTeam($contract->team)
            && $user->hasTeamRole($contract->team, 'admin');
    }

    /**
     * Only a team admin may permanently delete a contract.
     */
    public function forceDelete(User $user, Contract $contract): bool
    {
        return $user->belongsToTeam($contract->team)
            && $user->hasTeamRole($contract->team, 'admin');
    }

    /**
     * A user may sign a contract as long as they belong to the team
     * and have not already signed it.
     */
    public function sign(User $user, Contract $contract): bool
    {
        return $user->belongsToTeam($contract->team)
            && ! $contract->signatures()->where('user_id', $user->id)->exists();
    }
}
