<?php

namespace Litlife\EnotIoPayments\Exceptions;

class UserNotFoundResponseException extends ApiResponseException
{
    protected $message = 'The user was not found (the API key or email address are specified incorrectly)';
}
