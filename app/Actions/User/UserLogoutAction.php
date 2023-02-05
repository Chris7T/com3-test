<?php

namespace App\Actions\User;

use Illuminate\Support\Facades\Auth;

class UserLogoutAction
{
    public function execute(): void
    {
        Auth::user()->tokens()->delete();
    }
}
