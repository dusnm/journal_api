<?php
/**
 * Used to register the user
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Services;

use App\DTO\User\RegistrationDTO;
use App\Exceptions\UserAlreadyExistsException;
use App\Models\User;

class RegistrationService
{
    /**
     * @param RegistrationDTO $dto
     *
     * @throws App\Exceptions\UserAlreadyExistsException
     *
     * @return App\Models\User
     */
    public function register(RegistrationDTO $dto): User
    {
        $user = User::query()->where('email', '=', $dto->email)->first();

        if ($user instanceof User) {
            throw new UserAlreadyExistsException();
        }

        return User::query()->create([
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'email' => $dto->email,
            'password' => password_hash($dto->password, PASSWORD_BCRYPT),
        ]);
    }
}
