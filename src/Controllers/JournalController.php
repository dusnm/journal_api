<?php

namespace App\Controllers;

use App\DTO\Journal\CreateJournalDTO;
use App\DTO\Journal\DeleteJournalDTO;
use App\DTO\Journal\ReadJournalDTO;
use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Services\JournalService;
use Illuminate\Database\QueryException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;

class JournalController extends ApiController
{
    private JournalService $journalService;
    private Validator $validator;
    private Logger $log;

    public function __construct(JournalService $journalService, Validator $validator, Logger $log)
    {
        $this->journalService = $journalService;
        $this->validator = $validator;
        $this->log = $log;
    }

    public function read(Request $request, Response $response): Response
    {
        $error = $request->getAttribute('error');
        $decodedData = $request->getAttribute('decodedData');

        if (isset($error)) {
            return $this->response($response, ['error' => $error], HttpStatusCodes::UNAUTHORIZED);
        }

        $readJournalDTO = new ReadJournalDTO((int) $decodedData->id);

        $validation = $this->validator->validate((array) $readJournalDTO, [
            'userId' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            $journals = $this->journalService->read($readJournalDTO);

            return $this->response($response, $journals, HttpStatusCodes::OK);
        } catch (QueryException $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request, Response $response): Response
    {
        $error = $request->getAttribute('error');
        $decodedData = $request->getAttribute('decodedData');

        if (isset($error)) {
            return $this->response($response, ['error' => $error], HttpStatusCodes::UNAUTHORIZED);
        }

        $requestBody = $request->getParsedBody();

        $createJournalDTO = new CreateJournalDTO(
            htmlspecialchars(strip_tags($requestBody['name'])),
            htmlspecialchars(strip_tags($requestBody['body'])),
            (int) $decodedData->id
        );

        $validation = $this->validator->validate((array) $createJournalDTO, [
            'name' => 'required',
            'body' => 'required',
            'userId' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            $journal = $this->journalService->create($createJournalDTO);

            return $this->response($response, $journal, HttpStatusCodes::CREATED);
        } catch (QueryException $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request, Response $response, $id): Response
    {
        $error = $request->getAttribute('error');
        $decodedData = $request->getAttribute('decodedData');

        if (isset($error)) {
            return $this->response($response, ['error' => $error], HttpStatusCodes::UNAUTHORIZED);
        }

        $deleteJournalDTO = new DeleteJournalDTO(
            (int) htmlspecialchars(strip_tags($id)),
            (int) $decodedData->id
        );

        $validation = $this->validator->validate((array) $deleteJournalDTO, [
            'id' => 'required|numeric',
            'userId' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            $this->journalService->delete($deleteJournalDTO);

            return $this->response($response, null, HttpStatusCodes::NO_CONTENT);
        } catch (QueryException $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
