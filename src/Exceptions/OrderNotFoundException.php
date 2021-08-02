<?php

namespace Litlife\EnotIoPayments\Exceptions;

class OrderNotFoundException extends ApiResponseException
{
    protected $message = 'Transaction not found';
}
