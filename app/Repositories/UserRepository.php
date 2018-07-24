<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserInterface;

class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function findOrCreateUser($user)
    {
        $authUser = User::where('g_id', $user->getId())->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'name'     => $user->getName(),
            'email'    => $user->getEmail(),
            'g_id' => $user->getId(),
            'g_avatar_url' => $user->getAvatar(),
        ]);
    }

    public function getAllPermission($id)
    {
        return $this->model->findOrFail($id)->getPermissionsViaRoles()->pluck('name')->unique()->toArray();
    }
}
