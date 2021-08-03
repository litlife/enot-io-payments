<?php

namespace Litlife\EnotIoPayments;

use GuzzleHttp\Client;
use InvalidArgumentException;
use Litlife\EnotIoPayments\Exceptions\InvalidSignatureException;
use Litlife\EnotIoPayments\Requests\PaymentStatusRequest;
use Litlife\EnotIoPayments\Requests\PayoutStatusRequest;
use Litlife\EnotIoPayments\Responses\BalanceResponse;
use Litlife\EnotIoPayments\Responses\PaymentInfoResponse;
use Litlife\EnotIoPayments\Responses\PaymentMethodResponse;
use Litlife\EnotIoPayments\Responses\PayoffInfoResponse;
use Litlife\EnotIoPayments\Responses\PayoffResponse;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class EnotIoApi
{
    public $allowedPayoutServices = ['qw', 'cd', 'ya', 'ad', 'pm', 'wm', 'pa'];
    private $merchant_id;  // Shop ID in Enot.io system
    private $secret_key; // Secret key from the shop settings
    private $secret_key2; // Secret key 2 from the shop settings
    private $api_key; // The API key of your profile
    private $email; // EMAIL of your profile
    private $url = 'https://enot.io'; // URL of the payment aggregator
    private $httpClientConfig;

    /**
     * Set shop ID
     *
     * @param int $merchant_id
     * @return \Litlife\EnotIoPayments\EnotIoApi $this
     */
    public function setMerchantId(int $merchant_id): EnotIoApi
    {
        $this->merchant_id = $merchant_id;
        return $this;
    }

    /**
     * Set secret key
     *
     * @param string $secret_key
     * @return \Litlife\EnotIoPayments\EnotIoApi $this
     */
    public function setSecretKey(string $secret_key): EnotIoApi
    {
        $this->secret_key = $secret_key;
        return $this;
    }

    /**
     * Set secret key 2
     *
     * @param string $secret_key2
     * @return \Litlife\EnotIoPayments\EnotIoApi $this
     */
    public function setSecretKey2(string $secret_key2): EnotIoApi
    {
        $this->secret_key2 = $secret_key2;
        return $this;
    }

    /**
     * Set user email
     *
     * @param string $email
     * @return \Litlife\EnotIoPayments\EnotIoApi $this
     */
    public function setEmail(string $email): EnotIoApi
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Set api key
     *
     * @param string $apiKey
     * @return \Litlife\EnotIoPayments\EnotIoApi $this
     */
    public function setApiKey(string $apiKey): EnotIoApi
    {
        $this->api_key = $apiKey;
        return $this;
    }

    /**
     * Get array of allowed payout services
     *
     * @return array
     */
    public function getAllowedPayoutServices(): array
    {
        return $this->allowedPayoutServices;
    }

    /**
     * Set array of allowed payout services
     *
     * @param array $allowedPayoutServices
     * @return \Litlife\EnotIoPayments\EnotIoApi $this
     */
    public function setAllowedPayoutServices(array $allowedPayoutServices): EnotIoApi
    {
        $this->allowedPayoutServices = $allowedPayoutServices;
        return $this;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return \Litlife\EnotIoPayments\EnotIoApi $this
     */
    public function setUrl(string $url): EnotIoApi
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set guzzle http client config
     *
     * @param array $config
     * @return \Litlife\EnotIoPayments\EnotIoApi $this
     */
    public function setHttpClientConfig(array $config = []): EnotIoApi
    {
        $this->httpClientConfig = $config;
        return $this;
    }

    /**
     * Generating a payment URL
     *
     * @param float $orderAmount The amount to be paid
     * @param string $orderId Payment ID in your system
     * @param string $currency Payment currency (RUB, USD, EUR, UAH)
     * @param string|null $comment Payment comment (shown to the client when paying)
     * @param null $cf A string that will be returned to notifications after payment
     * @param string|null $p Initially, choose a payment method
     * @param int|null $ap Direct forwarding to the payment system (Currently available only for QIWI)
     * @param string|null $success_url The URL where to redirect the user, after a successful payment. (If not filled in, the value is taken from the shop settings. This parameter is a priority for redirects)
     * @param string|null $fail_url The URL where to redirect the user, after an error during payment (If not filled in, the value is taken from the shop settings. This parameter is a priority for redirects)
     * @return string  SIGN key
     */
    public function getPaymentUrl(
        float $orderAmount,
        string $orderId,
        string $currency = 'RUB',
        string $comment = null,
        $cf = null,
        string $p = null,
        int $ap = null,
        string $success_url = null,
        string $fail_url = null
    ): string
    {
        if (empty($this->merchant_id))
            throw new RuntimeException('Merchant Id is not defined');

        if (empty($this->secret_key))
            throw new RuntimeException('Secret key is not defined');

        $orderAmount = $this->moneyFormat($orderAmount);

        $query = http_build_query([
            'm' => $this->merchant_id,
            'oa' => $orderAmount,
            'o' => $orderId,
            's' => $this->getSignature($this->merchant_id, $orderAmount, $this->secret_key, $orderId),
            'cr' => $currency,
            'c' => $comment,
            'cf' => $cf,
            'p' => $p,
            'ap' => $ap,
            'success_url' => $success_url,
            'fail_url' => $fail_url
        ]);

        return $this->url . '/pay?' . $query;
    }

    /**
     * Generating the SIGN key
     *
     * @param int $merchant_id Merchant ID in Enot.io system
     * @param float $order_amount Amount to be paid
     * @param string $secret_word The secret key. Specified in the shop's settings. (For SIGN_2, use the second secret key)
     * @param int $payment_id Order ID in your system
     * @return string  SIGN key
     */
    public function getSignature(int $merchant_id, float $order_amount, string $secret_word, int $payment_id): string
    {
        return md5($merchant_id . ':' . $order_amount . ':' . $secret_word . ':' . $payment_id);
    }

    /**
     * Generating the SIGN key 2
     *
     * @param int $merchant_id Shop ID in Enot.io system
     * @param float $order_amount Amount to be paid
     * @param string $secret_word2 The secret key 2. Specified in the shop's settings
     * @param int $payment_id Order ID in your system
     * @return string  SIGN key
     */
    public function getSignature2(int $merchant_id, float $order_amount, string $secret_word2, int $payment_id): string
    {
        return md5($merchant_id . ':' . $order_amount . ':' . $secret_word2 . ':' . $payment_id);
    }

    /**
     * Get information about the payment
     *
     * @param int|null $shop_id Your shop ID
     * @param int|null $id Transaction ID (In Enot.io system)
     * @param int|null $oid Transaction ID (In your system)
     * @return PaymentInfoResponse
     * @throws \InvalidArgumentException|\Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function paymentInfo(int $shop_id, int $id = null, int $oid = null): PaymentInfoResponse
    {
        if (empty($id) and empty($oid))
            throw new InvalidArgumentException('You must pass either the payment number or the payment number in your system');

        $this->assertApiKeyIsSet();
        $this->assertEmailIsSet();

        $url = $this->url . '/request/payment-info';

        $response = $this->request($url, [
            'params' => [
                'api_key' => $this->api_key,
                'email' => $this->email,
                'shop_id' => $shop_id,
                'id' => $id,
                'oid' => $oid
            ]
        ]);

        return new PaymentInfoResponse($response);
    }

    /**
     * Assert that api key is filled in
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function assertApiKeyIsSet()
    {
        if (empty($this->api_key))
            throw new InvalidArgumentException('The api key is not set');
    }

    /**
     * Assert that email is filled in
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function assertEmailIsSet()
    {
        if (empty($this->email))
            throw new InvalidArgumentException('The email is not set');
    }

    /**
     * Guzzle request
     *
     * @param string $url
     * @param array $query
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $url, array $query): ResponseInterface
    {
        $client = new Client($this->httpClientConfig);

        return $client->request('GET', $url, [
            'query' => $query
        ]);
    }

    /**
     * Get payment methods list
     *
     * @return PaymentMethodResponse
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function paymentMethods(): PaymentMethodResponse
    {
        $this->assertSecretKey();
        $this->assertMerchantIdIsSet();

        $url = $this->url . '/request/payment-methods';

        $response = $this->request($url, [
            'params' => [
                'merchant_id' => $this->merchant_id,
                'secret_key' => $this->secret_key
            ]
        ]);

        return new PaymentMethodResponse($response);
    }

    /**
     * Assert that secret key is filled in
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function assertSecretKey()
    {
        if (empty($this->secret_key))
            throw new InvalidArgumentException('The secret key is not set');
    }

    /**
     * Assert that secret key2 is filled in
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function assertSecretKey2()
    {
        if (empty($this->secret_key2))
            throw new InvalidArgumentException('The secret key 2 is not set');
    }

    /**
     * Assert that shop ID is filled in
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function assertMerchantIdIsSet()
    {
        if (empty($this->merchant_id))
            throw new InvalidArgumentException('The shop ID is not set');
    }

    /**
     * Get balance info
     *
     * @return BalanceResponse
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function balance(): BalanceResponse
    {
        $this->assertEmailIsSet();
        $this->assertApiKeyIsSet();

        $url = $this->url . '/request/balance';

        $response = $this->request($url, [
            'params' => [
                'email' => $this->email,
                'api_key' => $this->api_key
            ]
        ]);

        return new BalanceResponse($response);
    }

    /**
     * Withdrawal of funds
     *
     * @param string $service A service for withdrawing funds. (list of output services)
     * @param string $wallet The number of the wallet to withdraw. (examples of wallets)
     * @param float $amount The amount to withdraw.
     * @param string|null $orderId The payout number in your system (Must be unique)
     * @return PayoffResponse
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function payoff(string $service, string $wallet, float $amount, string $orderId = ''): PayoffResponse
    {
        $this->validatePayoutService($service);
        $this->assertEmailIsSet();
        $this->assertApiKeyIsSet();

        $url = $this->url . '/request/payoff';

        $response = $this->request($url, [
            'params' => [
                'api_key' => $this->api_key,
                'email' => $this->email,
                'service' => $service,
                'wallet' => $wallet,
                'amount' => $amount,
                'orderid' => $orderId
            ]
        ]);

        return new PayoffResponse($response);
    }

    /**
     * Validate payout service
     *
     * @param string $service Payment method code
     * @return void
     * @throws \InvalidArgumentException
     */
    public function validatePayoutService(string $service)
    {
        if (!in_array($service, $this->allowedPayoutServices))
            throw new InvalidArgumentException('The service "' . $service . '" for withdrawing money is not available');
    }

    /**
     * Payment Information
     *
     * @param int|null $id Payment number in Enot.io system
     * @param string|null $orderId The payment number in your system
     * @return PayoffInfoResponse
     * @throws \InvalidArgumentException|\Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function payoffInfo(int $id = null, string $orderId = ''): PayoffInfoResponse
    {
        if (empty($id) and empty($orderId))
            throw new InvalidArgumentException('You must pass either the payout number or the payout number in your system');

        $this->assertEmailIsSet();
        $this->assertApiKeyIsSet();

        $url = $this->url . 'request/payoff-info';

        $response = $this->request($url, [
            'params' => [
                'api_key' => $this->api_key,
                'email' => $this->email,
                'id' => $id,
                'orderid' => $orderId
            ]
        ]);

        return new PayoffInfoResponse($response);
    }

    /**
     * Notifications after payment (webhook, callback)
     *
     * @param array $params
     * @return \Litlife\EnotIoPayments\Requests\PaymentStatusRequest
     * @throws \Exception
     */
    public function paymentStatus(array $params): PaymentStatusRequest
    {
        $this->assertSecretKey2();

        $request = new PaymentStatusRequest($params);

        $signature = $this->getSignature2($request->getMerchant(), $request->getAmount(), $this->secret_key2, $request->getMerchantId());

        if ($signature != $request->getSign2())
            throw new InvalidSignatureException();

        return $request;
    }

    /**
     * Payout notification (webhook, callback)
     *
     * @param array $params
     * @return \Litlife\EnotIoPayments\Requests\PayoutStatusRequest
     * @throws \Exception
     */
    public function payoutStatus(array $params): PayoutStatusRequest
    {
        return new PayoutStatusRequest($params);
    }

    /**
     * Formatting a money number to a standard
     *
     * @param float $number
     * @return string
     */
    public function moneyFormat(float $number) :string
    {
        return (string)$number;
    }
}
