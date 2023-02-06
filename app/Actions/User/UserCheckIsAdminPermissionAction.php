<?php

namespace App\Actions\User;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserCheckIsAdminPermissionAction
{
    public function execute(): void
    {
        $userIsAdmin = Auth::user()->is_admin;

        if (!$userIsAdmin) {
            throw new AccessDeniedHttpException('You do not have permission');
        }
    }
}
