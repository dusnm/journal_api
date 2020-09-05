<?php
/**
 * Creates Swift_Message instances
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Factories;

use App\Interfaces\MessageFactoryInterface;
use Error;
use InvalidArgumentException;
use Swift_Message;

use function App\Helpers\env;

class MessageFactory implements MessageFactoryInterface
{
    public function produce(string $subject, string $from, string $to, string $contentType = null, array $options): Swift_Message
    {
        $message = new Swift_Message($subject);
        $message->setFrom($from);
        $message->setTo($to);

        if (!array_key_exists('message_type', $options)) {
            throw new Error('Message type not specified.');
        }

        if ($options['message_type'] === static::VERIFICATION_MESSAGE) {
            if (!array_key_exists('token', $options)) {
                throw new Error('Token not supplied.');
            }

            $message->setBody(
                '<a href="'.env('APP_URL').'/api/user/verify?token='.$options['token'].'">Click here to verify your account.</a>',
                $contentType
            );

            return $message;
        }

        if ($options['message_type'] === static::RESET_MESSAGE) {
            if (!array_key_exists('token', $options)) {
                throw new Error('Token not supplied.');
            }

            $message->setBody(
                '<a href="'.env('APP_URL').'/api/user/password-reset?token='.$options['token'].'">Click here to reset your password.</a>',
                $contentType
            );

            return $message;
        }

        throw new InvalidArgumentException('Message type unknown.');
    }
}
