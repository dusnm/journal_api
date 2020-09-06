<?php
/*
 * Validation error messages
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Interfaces;

interface ErrorMessages
{
    public const SERVER_ERROR = 'Server error, please try again later.';
    public const NOT_FOUND = 'The requested resource was not found.';
    public const UNAUTHORIZED = 'You are not authorized to perform this action.';
    public const DUAL_AUTHORIZATION_TYPE = 'You can only use one authorization type at a time.';
    public const INVALID_AUTHORIZATION_TYPE = 'Invalid authorization type.';
    public const LOGIN_FAILED = 'Check your email or password.';
    public const DUPLICATE_EMAIL = 'Email taken.';
}
