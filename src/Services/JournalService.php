<?php

namespace App\Services;

use App\DTO\Journal\CreateJournalDTO;
use App\DTO\Journal\DeleteJournalDTO;
use App\DTO\Journal\ReadByIdJournalDTO;
use App\DTO\Journal\ReadJournalDTO;
use App\DTO\Journal\UpdateJournalDTO;
use App\Models\Journal;

class JournalService
{
    public function readById(ReadByIdJournalDTO $readByIdJournalDTO)
    {
        return Journal::query()->where([['id', '=', $readByIdJournalDTO->id], ['user_id', '=', $readByIdJournalDTO->userId]])->firstOrFail();
    }

    public function read(ReadJournalDTO $readJournalDTO): array
    {
        return [
            'journals' => Journal::query()
                ->where('user_id', '=', $readJournalDTO->userId)
                ->orderBy('created_at', 'DESC')
                ->skip(($readJournalDTO->page - 1) * $readJournalDTO->rowsPerPage)
                ->take($readJournalDTO->rowsPerPage)
                ->get(),
            'totalPages' => ceil(Journal::query()->count() / $readJournalDTO->rowsPerPage),
        ];
    }

    public function create(CreateJournalDTO $createJournalDTO): Journal
    {
        return Journal::query()->create([
            'name' => $createJournalDTO->name,
            'body' => $createJournalDTO->body,
            'user_id' => $createJournalDTO->userId,
        ]);
    }

    public function update(UpdateJournalDTO $updateJournalDTO): int
    {
        return Journal::query()
            ->where([
                ['id', '=', $updateJournalDTO->id],
                ['user_id', '=', $updateJournalDTO->userId],
            ])
            ->update([
                'name' => $updateJournalDTO->name,
                'body' => $updateJournalDTO->body,
            ])
        ;
    }

    public function delete(DeleteJournalDTO $deleteJournalDTO)
    {
        return Journal::query()
            ->where([['id', '=', $deleteJournalDTO->id], ['user_id', '=', $deleteJournalDTO->userId]])
            ->delete()
        ;
    }
}
