<?php

namespace App\Policies;

use App\Models\User;

abstract class BasePolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === User::ROLE_SUPER_ADMIN ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $this->isKnownRole($user);
    }

    public function view(User $user, mixed $model): bool
    {
        return $this->isKnownRole($user);
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user);
    }

    public function update(User $user, mixed $model): bool
    {
        return $this->canWrite($user);
    }

    public function delete(User $user, mixed $model): bool
    {
        return $user->role === User::ROLE_OWNER;
    }

    public function restore(User $user, mixed $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, mixed $model): bool
    {
        return false;
    }

    protected function isKnownRole(User $user): bool
    {
        return in_array($user->role, User::ROLES, true);
    }

    protected function canWrite(User $user): bool
    {
        return in_array($user->role, [
            User::ROLE_ADMIN_LOKET,
            User::ROLE_AGEN_TUJUAN,
            User::ROLE_OWNER,
        ], true);
    }
}
