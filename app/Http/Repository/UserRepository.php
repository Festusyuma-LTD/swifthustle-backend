<?php

namespace App\Http\Repository;

use App\User;


class UserRepository {

    protected $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create($data) {
        return $this->user->create($data);
    }

}