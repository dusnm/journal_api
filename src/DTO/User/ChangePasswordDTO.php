<?php

namespace App\DTO\User;

class ChangePasswordDTO
{
    public string $email;
    public string $oldPassword;
    public string $newPassword;

    public function __construct(string $email, string $oldPassword, string $newPassword)
    {
        $this->email = $email;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }
}
