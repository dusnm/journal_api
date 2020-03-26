<?php

namespace App\Services;

use App\DTO\Journal\CreateJournalDTO;
use App\DTO\Journal\DeleteJournalDTO;
use App\DTO\Journal\ReadJournalDTO;
use App\Models\Journal;

class JournalService
{
    public function readById()
    {
    }

    public function read(ReadJournalDTO $readJournalDTO)
    {
        return Journal::query()->where('user_id', '=', $readJournalDTO->userId)->get();
    }

    public function create(CreateJournalDTO $createJournalDTO): Journal
    {
        return Journal::query()->create([
            'name' => $createJournalDTO->name,
            'body' => $createJournalDTO->body,
            'user_id' => $createJournalDTO->userId,
        ]);
    }

    public function update()
    {
    }

    public function delete(DeleteJournalDTO $deleteJournalDTO)
    {
        return Journal::query()
            ->where([['id', '=', $deleteJournalDTO->id], ['user_id', '=', $deleteJournalDTO->userId]])
            ->delete()
        ;
    }
}
