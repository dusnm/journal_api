<?php

namespace App\DTO\Journal;

class ReadJournalDTO
{
    public int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
