<?php

namespace App\Controllers;

use App\DTO\User\RegistrationDTO;
use App\Exceptions\UserAlreadyExistsException;
use App\Factories\MessageFactory;
use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Interfaces\MessageFactoryInterface;
use App\Services\JwtService;
use App\Services\MailerService;
use App\Services\RegistrationService;
use Exception;
use Illuminate\Database\QueryException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;
use Swift_TransportException;
use function App\Helpers\env;
use function App\Helpers\formatException;

class RegistrationController extends ApiController
{
    private Validator $validator;
    private RegistrationService $registrationService;
    private MailerService $mailerService;
    private MessageFactory $messageFactory;
    private JwtService $jwtService;
    private Logger $log;

    public function __construct(
        Validator $validator,
        RegistrationService $registrationService,
        MailerService $mailerService,
        MessageFactory $messageFactory,
        JwtService $jwtService,
        Logger $log
    ) {
        $this->validator = $validator;
        $this->registrationService = $registrationService;
        $this->mailerService = $mailerService;
        $this->messageFactory = $messageFactory;
        $this->jwtService = $jwtService;
        $this->log = $log;
    }

    public function register(Request $request, Response $response): Response
    {
        $requestBody = $request->getParsedBody();

        $registrationDTO = new RegistrationDTO(
            htmlspecialchars(strip_tags($requestBody['firstName'])),
            htmlspecialchars(strip_tags($requestBody['lastName'])),
            htmlspecialchars(strip_tags($requestBody['email'])),
            htmlspecialchars(strip_tags($requestBody['password']))
        );

        $validation = $this->validator->validate((array) $registrationDTO, [
            'firstName' => 'required|max:25',
            'lastName' => 'required|max:25',
            'email' => 'required|email|max:50',
            'password' => 'required|min:8|max:100',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            $user = $this->registrationService->register($registrationDTO);
        } catch (UserAlreadyExistsException $e) {
            return $this->response($response, ['error' => ErrorMessages::DUPLICATE_EMAIL], HttpStatusCodes::UNPROCESSABLE_ENTITY);
        } catch (QueryException | Exception $e) {
            $this->log->error($e->getMessage(), array_merge(
                [
                    'route' => $request->getUri()->getPath(),
                    'email' => $registrationDTO->email
                ],
                formatException($e)
            ));

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }

        $verificationToken = $this->jwtService->sign(['email' => $registrationDTO->email], 60 * 10);

        $verificationMessage = $this->messageFactory->produce(
            env('APP_NAME', 'Journal API').' verification email.',
            env('MAILER_USERNAME'),
            $registrationDTO->email,
            'text/html',
            [
                'message_type' => MessageFactoryInterface::VERIFICATION_MESSAGE,
                'token' => $verificationToken,
            ]
        );

        try {
            if (0 === $this->mailerService->send($verificationMessage)) {
                $this->log->error('Failed to send an email to: '.$registrationDTO->email, [
                    'route' => $request->getUri()->getPath(),
                ]);
            } else {
                $this->log->info('Sent a verification email to: '.$registrationDTO->email, [
                    'route' => $request->getUri()->getPath(),
                ]);
            }

            return $this->response($response, $user, HttpStatusCodes::CREATED);
        } catch (Swift_TransportException | Exception $e) {
            $this->log->error($e->getMessage(), array_merge(
                [
                    'route' => $request->getUri()->getPath(),
                    'verification_message' => $verificationMessage->getBody(),
                ],
                formatException($e)
            ));

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
