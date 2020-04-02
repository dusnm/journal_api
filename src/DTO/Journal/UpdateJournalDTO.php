<?php

namespace App\DTO\Journal;

class UpdateJournalDTO
{
    public int $id;
    public int $userId;
    public string $name;
    public string $body;

    public function __construct(int $id, int $userId, string $name, string $body)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->body = $body;
    }
}
