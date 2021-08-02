<?php

namespace Litlife\EnotIoPayments\Exceptions;

class OneParameterEmptyException extends ApiResponseException
{
    protected $message = 'One of the parameters is empty';
}
