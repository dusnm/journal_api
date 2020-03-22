<?php

namespace App\Services;

use App\DTO\RegistrationDTO;
use App\Models\User;

class RegistrationService
{
    public function register(RegistrationDTO $dto): User
    {
        return User::query()->create([
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'email' => $dto->email,
            'password' => password_hash($dto->password, PASSWORD_BCRYPT),
        ]);
    }
}
