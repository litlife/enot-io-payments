<?php

namespace Litlife\EnotIoPayments\Responses;

use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\Exceptions\ApiResponseException;
use Litlife\EnotIoPayments\Exceptions\OneParameterEmptyException;
use Litlife\EnotIoPayments\Exceptions\UserNotFoundResponseException;

class BalanceResponse extends JsonResponse
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
            $message = $this->json['message'];

            if (empty($message))
                throw new ApiResponseException();

            if ($message == 'USER_NOT_FOUND')
                throw new UserNotFoundResponseException();
            elseif ($message == 'ONE_PARAMETR_EMPTY')
                throw new OneParameterEmptyException();
            else
                throw new ApiResponseException($message);
        }
    }

    /**
     * Getting a main balance
     *
     * @return float|null
     */
    public function getBalance(): ?float
    {
        return $this->json['balance'];
    }

    /**
     * Getting a frozen balance
     *
     * @return float|null
     */
    public function getBalanceFreeze(): ?float
    {
        return $this->json['balance_freeze'];
    }
}
