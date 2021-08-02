<?php

namespace Litlife\EnotIoPayments\Responses;

use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\Exceptions\ApiResponseException;
use Litlife\EnotIoPayments\Exceptions\OneParameterEmptyException;
use Litlife\EnotIoPayments\Exceptions\UserNotFoundResponseException;

class PayoffResponse extends JsonResponse
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
            elseif ($message == 'AMOUNT_SMALL')
                throw new ApiResponseException('The withdrawal amount is not included in the allowed range for withdrawal');
            elseif ($message == 'AMOUNT_BIG')
                throw new ApiResponseException('The withdrawal amount is not included in the allowed range for withdrawal');
            elseif ($message == 'SERVICE_NOT_FOUND')
                throw new ApiResponseException('The service for the output was not found');
            elseif ($message == 'SUM_ERROR')
                throw new ApiResponseException('Error entering the amount. The commission is not calculated');
            elseif ($message == 'WALLET_ON_BLACKLIST')
                throw new ApiResponseException('The wallet is blacklisted');
            elseif ($message == 'ORDER_ID_EXIST')
                throw new ApiResponseException('Not a unique payout number in your system');
            elseif ($message == 'BANK_NOT_ALLOWED')
                throw new ApiResponseException('Withdrawal to the card of this bank is not possible');
            elseif (preg_match('/^BALANCE_SMALL:([0-9.]+)$/iu', $message, $matches)) {
                throw new ApiResponseException('There are not enough funds for withdrawal. Your balance is: ' . $matches[1]);
            } else
                throw new ApiResponseException($message);
        }
    }

    /**
     * New balance (only if the response is successful)
     *
     * @return float|null
     */
    public function getBalance(): ?float
    {
        return $this->json['balance'];
    }

    /**
     * Getting payout transaction ID in Enot.io system (only if the response is successful)
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->json['id'] ?? null;
    }

    /**
     * Getting payout transaction ID in your system (only if the response is successful)
     *
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->json['orderid'] ?? null;
    }
}
