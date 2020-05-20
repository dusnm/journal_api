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
    /* A list of models to eager load */
    private array $eagerLoadedModels = [
        'images',
    ];

    /**
     * Get a single Journal record from the database identified by id and belonging to the user making a request
     *
     * @param \App\DTO\Journal\ReadByIdJournalDTO $readByIdJournalDTO
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \App\Models\Journal The retrieved Journal record
     */
    public function readById(ReadByIdJournalDTO $readByIdJournalDTO)
    {
        return Journal::query()
            ->with($this->eagerLoadedModels)
            ->where([
                ['id', '=', $readByIdJournalDTO->id],
                ['user_id', '=', $readByIdJournalDTO->userId]
            ])->firstOrFail();
    }

    /**
     * Get a paginated list of Journal records from the database belonging to the user making a request
     *
     * @param \App\DTO\Journal\ReadJournalDTO $readJournalDTO
     *
     * @return array paginated list of retrieved Journal records
     */
    public function read(ReadJournalDTO $readJournalDTO): array
    {
        return [
            'journals' => Journal::query()
                ->with($this->eagerLoadedModels)
                ->where('user_id', '=', $readJournalDTO->userId)
                ->orderBy('created_at', 'DESC')
                ->skip(($readJournalDTO->page - 1) * $readJournalDTO->rowsPerPage)
                ->take($readJournalDTO->rowsPerPage)
                ->get(),
            'total_pages' => ceil(
                Journal::query()
                    ->where('user_id', '=', $readJournalDTO->userId)
                    ->count() / $readJournalDTO->rowsPerPage
            ),
       ];
    }
    
    /**
     * Create a new Journal record in the database belonging to the user requesting creation
     *
     * @param \App\DTO\JournalCreateJournalDTO $createJournalDTO
     *
     * @return \App\Models\Journal an instance of the newly created Journal record
     */
    public function create(CreateJournalDTO $createJournalDTO): Journal
    {
        return Journal::query()->create([
            'name' => $createJournalDTO->name,
            'body' => $createJournalDTO->body,
            'user_id' => $createJournalDTO->userId,
        ]);
    }
    
    /**
     * Update the Journal record in the database belonging to the user requesting an update
     *
     * @param \App\DTO\Journal\UpdateJournalDTO $updateJournalDTO
     *
     * @return int 1 on success 0 on failure
     */
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

    /**
     * Delete a Journal record from the database belonging to the user requesting deletion
     *
     * @param \App\DTO\Journal\DeleteJournalDTO $deleteJournalDTO
     *
     * @return mixed 1 on success 0 on failure
     */
    public function delete(DeleteJournalDTO $deleteJournalDTO)
    {
        return Journal::query()
            ->where([['id', '=', $deleteJournalDTO->id], ['user_id', '=', $deleteJournalDTO->userId]])
            ->delete()
        ;
    }
}
