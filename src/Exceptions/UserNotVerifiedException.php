<?php
/**
 * Exception thrown upon login if the user has not verified their email
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Exceptions;

use Exception;
use Throwable;

class UserNotVerifiedException extends Exception
{
    public function __construct(string $message = 'Account not verified', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
