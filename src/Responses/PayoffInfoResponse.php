<?php

namespace Litlife\EnotIoPayments\Responses;

use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\Exceptions\ApiResponseException;
use Litlife\EnotIoPayments\Exceptions\OneParameterEmptyException;
use Litlife\EnotIoPayments\Exceptions\UserNotFoundResponseException;
use RuntimeException;

class PayoffInfoResponse extends JsonResponse
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
            else
                throw new ApiResponseException($this->getErrorMessage());
        }
    }

    public function getErrorMessage(): ?string
    {
        if (!$this->isError() and !$this->isFail())
            throw new RuntimeException('The response status must be an error or fail');

        return $this->json['message'];
    }

    public function isFail(): bool
    {
        return $this->getStatus() == 'fail';
    }

    public function isWait(): bool
    {
        return $this->getStatus() == 'wait';
    }

    /**
     * Getting ID of payout transaction
     *
     * @return int
     */
    public function getTransactionId(): int
    {
        return $this->json['transaction_id'];
    }

    /**
     * Getting ID of payout transaction in your system
     *
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->json['orderid'];
    }

    /**
     * Getting service for withdrawing funds
     *
     * @return string
     */
    public function getService(): string
    {
        return $this->json['service'];
    }

    /**
     * Getting account number where the payout was made
     *
     * @return string
     */
    public function getWallet(): string
    {
        return $this->json['wallet'];
    }

    /**
     * Getting payout sum
     *
     * @return float
     */
    public function getSum(): float
    {
        return $this->json['sum'];
    }

    /**
     * Getting commission amount
     *
     * @return float
     */
    public function getCommission(): float
    {
        return $this->json['commission'];
    }
}
