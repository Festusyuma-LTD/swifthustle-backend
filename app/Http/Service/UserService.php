<?php

namespace App\Http\Service;
use App\Http\Repository\UserRepository;


class UserService {

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

}