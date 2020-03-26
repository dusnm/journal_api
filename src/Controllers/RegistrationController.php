<?php

namespace App\Controllers;

use App\DTO\RegistrationDTO;
use function App\Helpers\env;
use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Models\User;
use App\Services\JwtService;
use App\Services\MailerService;
use App\Services\RegistrationService;
use Illuminate\Database\QueryException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;
use Swift_Message as Message;

class RegistrationController extends ApiController
{
    private Validator $validator;
    private RegistrationService $registrationService;
    private MailerService $mailerService;
    private JwtService $jwtService;
    private Logger $log;

    public function __construct(
        Validator $validator,
        RegistrationService $registrationService,
        MailerService $mailerService,
        JwtService $jwtService,
        Logger $log
    ) {
        $this->validator = $validator;
        $this->registrationService = $registrationService;
        $this->mailerService = $mailerService;
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
            'password' => 'required|min:6|max:100',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        $userOrError = $this->registrationService->register($registrationDTO);

        /*
            If there are no errors while inserting the user into the database the RegistrationService will return an instance of the eloquent User model.
            However since user email is a unique column in the database the database will throw an exception if the same value is inserted twice.
            The service returns two types of return values depending on if it can figure out what the exception is or not.
            If it can, it returns an error assoc array with a friendly error message to the user.
            If it cannot it'll simply forward the exception to the controller and the controller will return a server error.
         */
        if (!($userOrError instanceof User)) {
            if ($userOrError instanceof QueryException) {
                $this->log->error($userOrError->getMessage());

                return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
            }

            return $this->response($response, $userOrError, HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        $verificationToken = $this->jwtService->sign(['email' => $registrationDTO->email], 60 * 10);

        $verificationMessage = (new Message(env('APP_NAME', 'Journal API').' verification email.'))
            ->setFrom(env('MAILER_USERNAME'))
            ->setTo($registrationDTO->email)
            ->setBody('<a href='.env('APP_URL').'/api/verify?token='.$verificationToken.'>Click here to verify your account.</a>', 'text/html')
        ;

        if (0 === $this->mailerService->send($verificationMessage)) {
            $this->log->error('Failed to send an email to: '.$registrationDTO->email);
        } else {
            $this->log->info('Sent a verification email to: '.$registrationDTO->email);
        }

        return $this->response($response, $userOrError, HttpStatusCodes::CREATED);
    }
}
