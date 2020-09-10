<?php
/**
 * Email verification unit test
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Tests\Services;

use App\Services\EmailVerificationService;
use App\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailVerificationServiceTest extends TestCase
{
    public function testPasses()
    {
        $email = 'janedoe@example.com';
        $emailVerificationService = new EmailVerificationService();
        $verified = $emailVerificationService->verify($email);

        self::assertTrue($verified);
    }

    public function testModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);

        $email = 'nonexistingemail@example.com';
        $emailVerificationService = new EmailVerificationService();
        $emailVerificationService->verify($email);
    }
}
