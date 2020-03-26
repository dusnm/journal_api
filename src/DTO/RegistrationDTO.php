<?php

namespace App\DTO;

class RegistrationDTO
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $password;

    public function __construct(string $firstName, string $lastName, string $email, string $pasword)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $pasword;
    }
}
