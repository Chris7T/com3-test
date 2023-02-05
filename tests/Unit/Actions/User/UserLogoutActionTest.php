<?php

namespace Tests\Feature\Actions\User;

use App\Actions\User\UserLogoutAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserLogoutActionTest extends TestCase
{
    public function test_logout_deletes_user_tokens()
    {
        $user = $this->createMock(User::class);
        $tokens = $this->createMock(Sanctum::$personalAccessTokenModel);
        $tokens->expects($this->once())
            ->method('delete');
        $user->expects($this->once())
            ->method('tokens')
            ->willReturn($tokens);
        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $action = new UserLogoutAction();
        $action->execute();
    }
}
