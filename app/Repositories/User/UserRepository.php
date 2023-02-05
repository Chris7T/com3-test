<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly User $model
    ) {
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->model->firstWhere('email', $email);
    }

    public function createUser(
        string $name,
        string $email,
        string $phone,
        string $password
    ): User {
        return $this->model->create(
            [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
            ]
        );
    }
}
