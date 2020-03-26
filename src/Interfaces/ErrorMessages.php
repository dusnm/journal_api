<?php

namespace App\Interfaces;

interface ErrorMessages
{
    public const SERVER_ERROR = 'Server error, please trya gain later.';
    public const NOT_FOUND = 'The requested resource was not found.';
    public const UNAUTHORIZED = 'You are not authorized to perform this action.';
    public const DUAL_AUTHORIZATION_TYPE = 'You can only use one authorization type at a time.';
    public const INVALID_AUTHORIZATION_TYPE = 'Invalid authorization type.';
}
