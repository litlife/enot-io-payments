<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\EnotIoApi;
use Litlife\EnotIoPayments\Responses\PayoffInfoResponse;
use PHPUnit\Framework\TestCase;

class EnotIoApiPayoffInfoTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessfulResponse()
    {
        $body = '{"transaction_id":52407,"orderid" : "1","status":"fail","message":"Неверно указанные реквизиты","service":"qw","wallet":"79192131245","sum":"50.00","commission":"2.00"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $response = $api->payoffInfo(42, '24');

        $this->assertInstanceOf(PayoffInfoResponse::class, $response);

        $this->assertTrue($response->isFail());
        $this->assertEquals(52407, $response->getTransactionId());
        $this->assertEquals('1', $response->getOrderId());
        $this->assertEquals("Неверно указанные реквизиты", $response->getErrorMessage());
        $this->assertEquals("qw", $response->getService());
        $this->assertEquals("79192131245", $response->getWallet());
        $this->assertEquals(50.00, $response->getSum());
        $this->assertEquals(2.00, $response->getCommission());
    }

    public function testWaitedResponse()
    {
        $body = '{"transaction_id":52407,"orderid" : "1","status":"wait","message":"Неверно указанные реквизиты","service":"qw","wallet":"79192131245","sum":"50.00","commission":"2.00"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $response = $api->payoffInfo(42, '24');

        $this->assertInstanceOf(PayoffInfoResponse::class, $response);

        $this->assertTrue($response->isWait());
    }
}
