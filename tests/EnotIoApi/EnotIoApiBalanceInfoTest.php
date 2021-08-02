<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\EnotIoApi;
use Litlife\EnotIoPayments\Exceptions\OneParameterEmptyException;
use Litlife\EnotIoPayments\Exceptions\UserNotFoundResponseException;
use Litlife\EnotIoPayments\Responses\BalanceResponse;
use PHPUnit\Framework\TestCase;

class EnotIoApiBalanceInfoTest extends TestCase
{
    public function testSuccessfulResponse()
    {
        $body = '{"status":"success","balance":"95811.10","balance_freeze":"3849.00"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $response = $api->balance();

        $this->assertInstanceOf(BalanceResponse::class, $response);

        $this->assertEquals(95811.10, $response->getBalance());
        $this->assertEquals(3849.00, $response->getBalanceFreeze());

        $this->assertEquals([
            'status' => 'success',
            'balance' => 95811.10,
            'balance_freeze' => 3849.00
        ], $response->getJson());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUserNotFoundErrorResponse()
    {
        $body = '{"status":"error","message":"USER_NOT_FOUND"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $this->expectException(UserNotFoundResponseException::class);

        $response = $api->balance();

        $this->assertInstanceOf(BalanceResponse::class, $response);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testOneParameterEmptyErrorResponse()
    {
        $body = '{"status":"error","message":"ONE_PARAMETR_EMPTY"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $this->expectException(OneParameterEmptyException::class);

        $response = $api->balance();

        $this->assertInstanceOf(BalanceResponse::class, $response);
    }
}
