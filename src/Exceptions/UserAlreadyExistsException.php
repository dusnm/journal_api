<?php
/**
 * Exception thrown upon registration if the user is already in the database
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Exceptions;

use Exception;
use Throwable;

class UserAlreadyExistsException extends Exception
{
    public function __construct(string $message = 'User already exists.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
