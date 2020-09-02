<?php

declare(strict_types = 1);

namespace App\Tests\Services;

use App\DTO\User\LoginDTO;
use App\Exceptions\UserNotVerifiedException;
use App\Models\User;
use App\Services\LoginService;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoginServiceTest extends TestCase
{
    public function testHandlePasses()
    {
        $loginDTO = new LoginDTO($_ENV['TEST_USER_EMAIL'], $_ENV['TEST_USER_PASSWORD']);
        $loginService = new LoginService();

        $user = $loginService->login($loginDTO);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testHandleFails()
    {
        $loginDTO = new LoginDTO($_ENV['TEST_USER_EMAIL'], 'wrongpassword');
        $loginService = new LoginService();

        $user = $loginService->login($loginDTO);

        $this->assertNull($user);
    }

    public function testHandleFailsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);

        $loginDTO = new LoginDTO('nonexistingemail@example.com', 'somepassword');
        $loginService = new LoginService();

        $loginService->login($loginDTO);
    }

    public function testHandleFailsUserNotVerifiedException()
    {
        $this->expectException(UserNotVerifiedException::class);

        $loginDTO = new LoginDTO('unverified@mailinator.com', 'blabla1');
        $loginService = new LoginService();

        $loginService->login($loginDTO);
    }
}
