<?php

namespace Litlife\EnotIoPayments\Requests;

class PaymentStatusRequest
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
            'merchant' => $this->getMerchant(),
            'amount' => $this->getAmount(),
            'credited' => $this->getCredited(),
            'intid' => $this->getIntId(),
            'merchant_id' => $this->getMerchantId(),
            'method' => $this->getMethod(),
            'sign' => $this->getSign(),
            'sign_2' => $this->getSign2(),
            'currency' => $this->getCurrency(),
            'commission' => $this->getCommission(),
            'payer_details' => $this->getPayerDetails(),
            'custom_field' => $this->getCustomField()
        ];
    }

    /**
     * Your shop's ID
     *
     * @return int
     */
    public function getMerchant(): int
    {
        return $this->params['merchant'];
    }

    /**
     * Order amount
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->params['amount'];
    }

    /**
     * The amount credited to your balance (In rubles)
     *
     * @return float
     */
    public function getCredited(): float
    {
        return $this->params['credited'];
    }

    /**
     * ID of the operation in our system
     *
     * @return int
     */
    public function getIntId(): int
    {
        return $this->params['intid'];
    }

    /**
     * ID of the operation in your system
     *
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->params['merchant_id'];
    }

    /**
     * The payment system type
     *
     * @return string
     */
    public function getMethod(): ?string
    {
        return $this->params['method'];
    }

    /**
     * The key that you generated before paying for the order
     *
     * @return string
     */
    public function getSign(): string
    {
        return $this->params['sign'];
    }

    /**
     * The key that is generated as SIGN, but with the secret key no.2. Always check this key!
     *
     * @return string
     */
    public function getSign2(): string
    {
        return $this->params['sign_2'];
    }

    /**
     * Payment currency (RUB, USD, EUR, UAH) (Depends on the store's currency. By default, RUB)
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->params['currency'];
    }

    /**
     * The amount of the commission when ordering (Depends on the payment currency. By default, RUB)
     *
     * @return float
     */
    public function getCommission(): float
    {
        return $this->params['commission'];
    }

    /**
     * Details of the payer (Can be empty)
     *
     * @return string|null
     */
    public function getPayerDetails(): ?string
    {
        return $this->params['payer_details'];
    }

    /**
     * The string or array that you passed to the "cf" parameter"
     *
     * @return array|string
     */
    public function getCustomField()
    {
        return $this->params['custom_field'];
    }
}
