<?php

namespace App\DTO\Journal;

class DeleteJournalDTO
{
    public int $id;
    public int $userId;

    public function __construct(int $id, int $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }
}
