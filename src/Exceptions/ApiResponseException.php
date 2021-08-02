<?php

namespace Litlife\EnotIoPayments\Exceptions;

use RuntimeException;

class ApiResponseException extends RuntimeException
{
    protected $message = 'The API response returned an error';
}
