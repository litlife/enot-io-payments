<?php

namespace Litlife\EnotIoPayments\Exceptions;

class MerchantNotFoundResponseException extends ApiResponseException
{
    protected $message = 'The store was not found or not activated';
}
