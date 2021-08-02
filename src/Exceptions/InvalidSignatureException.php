<?php

namespace Litlife\EnotIoPayments\Exceptions;

class InvalidSignatureException extends ApiResponseException
{
    protected $message = 'The signature is incorrect';
}
