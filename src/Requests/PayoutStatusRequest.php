<?php

namespace Litlife\EnotIoPayments\Requests;

class PayoutStatusRequest
{
    private $params;

    /**
     * Construct
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * The payment system type
     *
     * @return array
     */
    public function getParams(): array
    {
        return [
            'status' => $this->getStatus(),
            'transaction_id' => $this->getTransactionId(),
            'orderid' => $this->getOrderId(),
            'message' => $this->getMessage(),
            'service' => $this->getService(),
            'wallet' => $this->getWallet(),
            'sum' => $this->getSum(),
            'commission' => $this->getCommission()
        ];
    }

    /**
     * The output status. success / fail
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->params['status'];
    }

    /**
     * Transaction ID (issued when creating a payment)
     *
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->params['transaction_id'];
    }

    /**
     * The payment number in your system
     *
     * @return string
     */
    public function getOrderId(): ?string
    {
        return $this->params['orderid'];
    }

    /**
     * Error message (you can give it to the client)
     *
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->params['message'];
    }

    /**
     * Service for withdrawing funds
     *
     * @return string
     */
    public function getService(): string
    {
        return $this->params['service'];
    }

    /**
     * The account where the withdrawal was made
     *
     * @return string
     */
    public function getWallet(): string
    {
        return $this->params['wallet'];
    }

    /**
     * Withdrawal amount
     *
     * @return float
     */
    public function getSum(): float
    {
        return $this->params['sum'];
    }

    /**
     * Commission
     *
     * @return float
     */
    public function getCommission(): float
    {
        return $this->params['commission'];
    }
}
