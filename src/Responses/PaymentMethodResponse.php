<?php

namespace Litlife\EnotIoPayments\Responses;

use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\Exceptions\ApiResponseException;
use Litlife\EnotIoPayments\Exceptions\MerchantNotFoundResponseException;
use Litlife\EnotIoPayments\Exceptions\NoPaymentMethodsEnabledException;

class PaymentMethodResponse extends JsonResponse
{
    protected $response;
    protected $contents;
    protected $json;

    /**
     * Construct response
     *
     * @param Response $response
     * @throws \Exception
     */
    public function __construct(Response $response)
    {
        parent::__construct($response);

        if ($this->isError()) {
            if (isset($this->json['methods']) and $this->json['methods'] == 'empty')
                throw new NoPaymentMethodsEnabledException();

            $message = $this->getErrorMessage();

            if (empty($message))
                throw new ApiResponseException();

            if ($message == 'merchant_not_found')
                throw new MerchantNotFoundResponseException();
            else
                throw new ApiResponseException($message);
        }
    }

    /**
     * Getting a payment methods
     *
     * @return string|array|null
     */
    public function getMethods()
    {
        return $this->json['methods'];
    }
}
