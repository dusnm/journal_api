<?php

namespace App\Controllers;

use App\DTO\Journal\CreateJournalDTO;
use App\DTO\Journal\DeleteJournalDTO;
use App\DTO\Journal\ReadByIdJournalDTO;
use App\DTO\Journal\ReadJournalDTO;
use App\DTO\Journal\UpdateJournalDTO;
use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Services\JournalService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function readById(Request $request, Response $response, $id): Response
    {
        $decodedData = $request->getAttribute('decodedData');

        $readByIdJournalDTO = new ReadByIdJournalDTO(
            (int) htmlspecialchars(strip_tags($id)),
            (int) $decodedData->id
        );

        $validation = $this->validator->validate((array) $readByIdJournalDTO, [
            'id' => 'required|numeric',
            'userId' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            $journal = $this->journalService->readById($readByIdJournalDTO);

            return $this->response($response, $journal, HttpStatusCodes::OK);
        } catch (ModelNotFoundException $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
                'dto' => $readByIdJournalDTO,
            ]);

            return $this->response($response, ['error' => ErrorMessages::NOT_FOUND], HttpStatusCodes::NOT_FOUND);
        } catch (QueryException | Exception $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
                'dto' => $readByIdJournalDTO,
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function read(Request $request, Response $response): Response
    {
        $decodedData = $request->getAttribute('decodedData');

        $requestQueryParams = $request->getQueryParams();

        $readJournalDTO = new ReadJournalDTO(
            (int) $decodedData->id,
            (int) htmlspecialchars(strip_tags($requestQueryParams['page'])),
            (int) htmlspecialchars(strip_tags($requestQueryParams['rowsPerPage']))
        );

        $validation = $this->validator->validate((array) $readJournalDTO, [
            'userId' => 'required|numeric',
            'page' => 'required|numeric',
            'rowsPerPage' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            $journals = $this->journalService->read($readJournalDTO);

            return $this->response($response, $journals, HttpStatusCodes::OK);
        } catch (QueryException | Exception $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
                'dto' => $readJournalDTO,
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request, Response $response): Response
    {
        $decodedData = $request->getAttribute('decodedData');

        $requestBody = $request->getParsedBody();

        $createJournalDTO = new CreateJournalDTO(
            htmlspecialchars(strip_tags($requestBody['name'])),
            htmlspecialchars(strip_tags($requestBody['body'])),
            (int) $decodedData->id
        );

        $validation = $this->validator->validate((array) $createJournalDTO, [
            'name' => 'required|max:50',
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
                'dto' => $createJournalDTO,
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Response $response, $id): Response
    {
        $decodedData = $request->getAttribute('decodedData');

        $requestBody = $request->getParsedBody();

        $updateJournalDTO = new UpdateJournalDTO(
            (int) htmlspecialchars(strip_tags($id)),
            (int) $decodedData->id,
            htmlspecialchars(strip_tags($requestBody['name'])),
            htmlspecialchars(strip_tags($requestBody['body']))
        );

        $validation = $this->validator->validate((array) $updateJournalDTO, [
            'name' => 'required|max:50',
            'body' => 'required',
            'userId' => 'required|numeric',
            'id' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            $updateStatus = $this->journalService->update($updateJournalDTO);

            return $this->response($response, ['updated' => $updateStatus], HttpStatusCodes::OK);
        } catch (QueryException | Exception $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
                'dto' => $updateJournalDTO,
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request, Response $response, $id): Response
    {
        $decodedData = $request->getAttribute('decodedData');

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
                'dto' => $deleteJournalDTO,
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
