<?php

namespace App\Repositories\Contracts;

interface UserInterface
{
    /**
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user);
}
