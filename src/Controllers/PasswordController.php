<?php

namespace App\Controllers;

use function App\Helpers\env;
use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Models\User;
use App\Services\JwtService;
use App\Services\MailerService;
use App\Services\PasswordService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;
use Swift_Message;

class PasswordController extends ApiController
{
    private PasswordService $passwordService;
    private MailerService $mailerService;
    private JwtService $jwtService;
    private Validator $validator;
    private Logger $log;

    public function __construct(PasswordService $passwordService, MailerService $mailerService, JwtService $jwtService, Validator $validator, Logger $log)
    {
        $this->passwordService = $passwordService;
        $this->mailerService = $mailerService;
        $this->jwtService = $jwtService;
        $this->validator = $validator;
        $this->log = $log;
    }

    public function requestPasswordReset(Request $request, Response $response): Response
    {
        $requestQueryParams = $request->getQueryParams();

        $validation = $this->validator->validate($requestQueryParams, [
            'email' => 'required|email',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            User::query()->where('email', '=', $requestQueryParams['email'])->firstOrFail();

            $token = $this->jwtService->sign(['email' => $requestQueryParams['email']], 60 * 10);

            $message = (new Swift_Message())
                ->addFrom(env('MAILER_USERNAME'))
                ->addTo($requestQueryParams['email'])
                ->setSubject(env('APP_NAME', 'Journal API').' password reset')
                ->setBody('<a href="'.env('APP_URL').'/api/user/password-reset?token='.$token.'">To reset your password, click here.</a>', 'text/html')
            ;

            if (0 === $this->mailerService->send($message)) {
                $this->log->error('Reset email not sent.', [
                    'route' => $request->getUri()->getPath(),
                    'email' => $requestQueryParams['email'],
                ]);

                return $this->response($response, ErrorMessages::SERVER_ERROR, HttpStatusCodes::INTERNAL_SERVER_ERROR);
            }

            $this->log->info('Reset email sent.', [
                'route' => $request->getUri()->getPath(),
                'email' => $requestQueryParams['email'],
            ]);

            return $this->response($response, ['success' => true], HttpStatusCodes::OK);
        } catch (ModelNotFoundException $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($response, ['error' => ErrorMessages::NOT_FOUND], HttpStatusCodes::NOT_FOUND);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
