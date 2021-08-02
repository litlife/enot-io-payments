<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\EnotIoApi;
use Litlife\EnotIoPayments\Exceptions\ApiResponseException;
use Litlife\EnotIoPayments\Responses\PayoffResponse;
use PHPUnit\Framework\TestCase;

class EnotIoApiPayoffTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessfulResponse()
    {
        $body = '{"status":"success", "balance":10.00, "id":1234}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $response = $api->payoff('qw', '79191111111', 42.20, 24);

        $this->assertInstanceOf(PayoffResponse::class, $response);

        $this->assertEquals(10.00, $response->getBalance());
        $this->assertEquals(1234, $response->getId());
        $this->assertEquals(null, $response->getOrderId());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testAmountSmallErrorResponse()
    {
        $body = '{"status":"error","message":"AMOUNT_SMALL"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $this->expectException(ApiResponseException::class);
        $this->expectErrorMessage('The withdrawal amount is not included in the allowed range for withdrawal');

        $response = $api->payoff('qw', '79191111111', 42.20, 24);

        $this->assertInstanceOf(PayoffResponse::class, $response);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testBalanceSmallErrorResponse()
    {
        $body = '{"status":"error","message":"BALANCE_SMALL:10.00"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $this->expectException(ApiResponseException::class);
        $this->expectErrorMessage('There are not enough funds for withdrawal. Your balance is: 10.00');

        $response = $api->payoff('qw', '79191111111', 42.20, 24);

        $this->assertInstanceOf(PayoffResponse::class, $response);
    }
}
