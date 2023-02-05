<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getUserByEmail(string $email): ?User;
    public function createUser(
        string $name,
        string $email,
        string $phone,
        string $password
    ): User;
}
