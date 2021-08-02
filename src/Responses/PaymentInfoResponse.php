<?php

namespace Litlife\EnotIoPayments\Responses;

use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\Exceptions\ApiResponseException;
use Litlife\EnotIoPayments\Exceptions\OneParameterEmptyException;
use Litlife\EnotIoPayments\Exceptions\OrderNotFoundException;
use Litlife\EnotIoPayments\Exceptions\UserNotFoundResponseException;

class PaymentInfoResponse extends JsonResponse
{
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
            $message = $this->json['message'];

            if (empty($message))
                throw new ApiResponseException();

            if ($message == 'USER_NOT_FOUND')
                throw new UserNotFoundResponseException();
            elseif ($message == 'ONE_PARAMETR_EMPTY')
                throw new OneParameterEmptyException();
            elseif ($message == 'ORDER_NOT_FOUND')
                throw new OrderNotFoundException();
            else
                throw new ApiResponseException($this->getErrorMessage());
        }
    }

    public function isCreated(): bool
    {
        return $this->getStatus() == 'created';
    }

    /**
     * Getting a shop ID
     *
     * @return string
     */
    public function getMerchant(): string
    {
        return $this->json['merchant'];
    }

    /**
     * Getting the payment amount
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->json['amount'];
    }

    /**
     * Getting the amount credited to the balance
     *
     * @return float
     */
    public function getCredited(): float
    {
        return $this->json['credited'];
    }

    /**
     * Getting ID of the transaction in Enot.io system
     *
     * @return int
     */
    public function getIntId(): int
    {
        return $this->json['intid'];
    }

    /**
     * Getting ID of the transaction in your system
     *
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->json['merchant_id'];
    }

    /**
     * Getting payment currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->json['currency'];
    }

    /**
     * Details of the payer (Can be empty)
     *
     * @return string|null
     */
    public function getPayerDetails(): ?string
    {
        return $this->json['payer_details'];
    }

    /**
     * The amount of the commission when ordering (Depends on the payment currency. By default, RUB)
     *
     * @return float
     */
    public function getCommission(): float
    {
        return $this->json['commission'];
    }

    /**
     * Getting string or array that you passed to the "cf" parameter
     *
     * @return string|array
     */
    public function getCustomField()
    {
        return $this->json['custom_field'];
    }

    /**
     * Getting the payment method code
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->json['method'];
    }
}
