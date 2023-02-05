<?php

namespace App\Actions\User;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Auth\Events\Registered;


class UserRegisterAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function execute(array $userData)
    {
        $user = $this->userRepository->createUser(
            name: Arr::get($userData, 'name'),
            email: Arr::get($userData, 'email'),
            phone: Arr::get($userData, 'phone'),
            password: Arr::get($userData, 'password'),
        );

        event(new Registered($user));

        $token = $user->createToken('User Login Token')->plainTextToken;

        return ['token' => $token];
    }
}
