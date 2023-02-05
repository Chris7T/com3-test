<?php

namespace App\Actions\User;

use Illuminate\Support\Facades\Hash;

class UserPasswordCheckAction
{
    public function execute(string $password, string $hashedPassword): bool
    {
        return Hash::check($password, $hashedPassword);
    }
}
