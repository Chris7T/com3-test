<?php

namespace Tests\Feature\Actions\User;

use App\Actions\User\UserLoginAction;
use App\Actions\User\UserPasswordCheckAction;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Laravel\Sanctum\NewAccessToken;
use Tests\TestCase;

class UserLoginActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryStub = $this->createMock(UserRepositoryInterface::class);
        $this->userPasswordCheckActionStub = $this->createMock(UserPasswordCheckAction::class);
    }

    public function test_expected_access_denied_http_exception_when_user_not_found()
    {
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Wrong credentials');

        $email = 'email@hotmail.com';
        $data = [
            'email' => $email,
            'password' => '12345678',
        ];
        $this->userRepositoryStub
            ->expects($this->once())
            ->method('getUserByEmail')
            ->with($email)
            ->willReturn(null);
        $service = new UserLoginAction(
            userRepositoryInterface: $this->userRepositoryStub,
            userPasswordCheckAction: $this->userPasswordCheckActionStub
        );

        $service->execute($data);
    }

    public function test_expected_access_denied_http_exception_when_password_is_wrong()
    {
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Wrong credentials');
        $password = '12345678';
        $email = 'email@hotmail.com';
        $data = [
            'email' => $email,
            'password' => $password,
        ];

        $user = new User();
        $user->email = $email;
        $user->password = bcrypt('87654321');
        $user->name = 'name';
        $user->phone = '99999999999';

        $this->userRepositoryStub
            ->expects($this->once())
            ->method('getUserByEmail')
            ->with($email)
            ->willReturn($user);
        $this->userPasswordCheckActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($password, $user->password)
            ->willReturn(false);
        $service = new UserLoginAction(
            userRepositoryInterface: $this->userRepositoryStub,
            userPasswordCheckAction: $this->userPasswordCheckActionStub
        );

        $service->execute($data);
    }

    public function test_expected_token_when_the_credentials_are_correct()
    {
        $email = 'email@hotmail.com';
        $password = 'correct-password';
        $passwordHashed = bcrypt('correct-password');
        $data = [
            'email' => $email,
            'password' => $password,
        ];

        $token = $this->createMock(NewAccessToken::class);
        $token->plainTextToken = 'mocked-token';

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('createToken')
            ->with('User Login Token')
            ->willReturn($token);
        $user->method('getAttribute')
            ->with('password')
            ->willReturn($passwordHashed);
        $this->userRepositoryStub
            ->expects($this->once())
            ->method('getUserByEmail')
            ->with($email)
            ->willReturn($user);
        $this->userPasswordCheckActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($password, $passwordHashed)
            ->willReturn(true);

        $service = new UserLoginAction(
            userRepositoryInterface: $this->userRepositoryStub,
            userPasswordCheckAction: $this->userPasswordCheckActionStub
        );
        $result = $service->execute($data);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('token', $result);
    }
}
