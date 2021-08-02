<?php

namespace Litlife\EnotIoPayments\Exceptions;

class NoPaymentMethodsEnabledException extends ApiResponseException
{
    protected $message = 'There are no payment methods enabled at the store';
}
