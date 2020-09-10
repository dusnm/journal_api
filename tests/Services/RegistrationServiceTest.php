<?php
/**
 * Registration unit test
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Tests\Services;

use App\DTO\User\RegistrationDTO;
use App\Exceptions\UserAlreadyExistsException;
use App\Models\User;
use App\Services\RegistrationService;
use App\Tests\TestCase;

class RegistrationServiceTest extends TestCase
{
    public function testPasses()
    {
        $registrationDTO = new RegistrationDTO(
            'Dusan',
            'Mitrovic',
            'dusan@example.com',
            'foobar3000'
        );

        $registrationService = new RegistrationService();
        $user = $registrationService->register($registrationDTO);

        self::assertInstanceOf(User::class, $user);
    }

    public function testUserAlreadyExistsException()
    {
        $this->expectException(UserAlreadyExistsException::class);

        $registrationDTO = new RegistrationDTO(
            'Jane',
            'Doe',
            'janedoe@example.com',
            'foobar3000'
        );

        $registrationService = new RegistrationService();
        $registrationService->register($registrationDTO);
    }
}
