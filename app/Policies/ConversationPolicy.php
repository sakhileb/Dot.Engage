<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Any authenticated user may start a new conversation.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * A user may view a conversation only if they are a participant.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->participants()->where('users.id', $user->id)->exists();
    }

    /**
     * Any authenticated user may create a conversation.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only team admins may rename a group conversation.
     */
    public function update(User $user, Conversation $conversation): bool
    {
        return $conversation->participants()->where('users.id', $user->id)->exists()
            && $user->hasTeamRole($conversation->team, 'admin');
    }

    /**
     * Only team admins may soft-delete a conversation.
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        return $user->hasTeamRole($conversation->team, 'admin');
    }

    /**
     * Only team admins may restore a conversation.
     */
    public function restore(User $user, Conversation $conversation): bool
    {
        return $user->hasTeamRole($conversation->team, 'admin');
    }

    /**
     * Only team admins may permanently delete a conversation.
     */
    public function forceDelete(User $user, Conversation $conversation): bool
    {
        return $user->hasTeamRole($conversation->team, 'admin');
    }

    /**
     * A participant may send messages to the conversation.
     */
    public function sendMessage(User $user, Conversation $conversation): bool
    {
        return $conversation->participants()->where('users.id', $user->id)->exists();
    }
}
