<?php

namespace App\Actions\User;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserLoginAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepositoryInterface,
        private readonly UserPasswordCheckAction $userPasswordCheckAction,
    ) {
    }

    public function execute(array $userData)
    {
        $email = Arr::get($userData, 'email');
        $password = Arr::get($userData, 'password');
        $user = $this->userRepositoryInterface->getUserByEmail($email);
        if (is_null($user) || !$this->userPasswordCheckAction->execute($password, $user->getAttribute('password'))) {
            throw new AccessDeniedHttpException('Wrong credentials');
        }

        $token = $user->createToken('User Login Token');

        return [
            'token' => $token->plainTextToken,
        ];
    }
}
