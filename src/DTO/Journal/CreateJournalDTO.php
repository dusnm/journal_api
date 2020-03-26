<?php

namespace App\DTO\Journal;

class CreateJournalDTO
{
    public string $name;
    public string $body;
    public int $userId;

    public function __construct(string $name, string $body, int $userId)
    {
        $this->name = $name;
        $this->body = $body;
        $this->userId = $userId;
    }
}
