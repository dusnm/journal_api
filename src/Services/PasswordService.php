<?php

namespace App\Services;

use App\DTO\User\ChangePasswordDTO;
use App\DTO\User\ResetPasswordDTO;
use App\Models\User;

class PasswordService
{
    public function changePassword(ChangePasswordDTO $changePasswordDTO): bool
    {
        $user = User::query()->where('email', '=', $changePasswordDTO->email)->firstOrFail();

        if (!password_verify($changePasswordDTO->oldPassword, $user->password)) {
            return false;
        }

        $user->password = password_hash($changePasswordDTO->newPassword, PASSWORD_BCRYPT);

        return $user->save();
    }

    public function resetPassword(ResetPasswordDTO $resetPasswordDTO): bool
    {
        $user = User::query()->where('email', '=', $resetPasswordDTO->email)->firstOrFail();
        $user->password = password_hash($resetPasswordDTO->password, PASSWORD_BCRYPT);

        return $user->save();
    }
}
