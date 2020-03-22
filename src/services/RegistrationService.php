<?php

namespace App\Services;

use App\DTO\RegistrationDTO;
use App\Interfaces\DatabaseErrors;
use App\Models\User;
use Illuminate\Database\QueryException;

class RegistrationService
{
    public function register(RegistrationDTO $dto)
    {
        try {
            return User::query()->create([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
                'email' => $dto->email,
                'password' => password_hash($dto->password, PASSWORD_BCRYPT),
            ]);
        } catch (QueryException $e) {
            switch ($e->getCode()) {
                case DatabaseErrors::ERR_DUPLICATE_ENTRY:
                    return [
                        'email' => 'Email taken.',
                    ];
                default:
                    return $e;
            }
        }
    }
}
