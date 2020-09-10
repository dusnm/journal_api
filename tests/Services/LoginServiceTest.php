<?php
/**
 * Login unit test
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Tests\Services;

use App\DTO\User\LoginDTO;
use App\Exceptions\UserNotVerifiedException;
use App\Models\User;
use App\Services\LoginService;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoginServiceTest extends TestCase
{
    public function testPasses()
    {
        $loginDTO = new LoginDTO('janedoe@example.com', 'janeiscool');
        $loginService = new LoginService();

        $user = $loginService->login($loginDTO);

        self::assertInstanceOf(User::class, $user);
    }

    public function testWrongPassword()
    {
        $loginDTO = new LoginDTO('janedoe@example.com', 'wrongpassword');
        $loginService = new LoginService();

        $user = $loginService->login($loginDTO);

        self::assertNull($user);
    }

    public function testFailsModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);

        $loginDTO = new LoginDTO('nonexistingemail@example.com', 'somepassword');
        $loginService = new LoginService();

        $loginService->login($loginDTO);
    }

    public function testFailsUserNotVerifiedException()
    {
        $this->expectException(UserNotVerifiedException::class);

        $loginDTO = new LoginDTO('michaelstonefist@example.com', 'toreadorsrule');
        $loginService = new LoginService();

        $loginService->login($loginDTO);
    }
}
