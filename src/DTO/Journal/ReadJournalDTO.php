<?php

namespace App\DTO\Journal;

class ReadJournalDTO
{
    public int $userId;
    public int $page;
    public int $rowsPerPage;

    public function __construct(int $userId, int $page, int $rowsPerPage)
    {
        $this->userId = $userId;
        $this->page = $page;
        $this->rowsPerPage = $rowsPerPage;
    }
}
