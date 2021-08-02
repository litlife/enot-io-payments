<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Litlife\EnotIoPayments\EnotIoApi;
use Litlife\EnotIoPayments\Exceptions\OneParameterEmptyException;
use Litlife\EnotIoPayments\Exceptions\OrderNotFoundException;
use Litlife\EnotIoPayments\Exceptions\UserNotFoundResponseException;
use Litlife\EnotIoPayments\Responses\PaymentInfoResponse;
use PHPUnit\Framework\TestCase;

class EnotIoApiPaymentInfoTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessfulResponse()
    {
        $body = '{"merchant":1,"status":"success","amount":"1.00","credited":"0.98","intid":650611,"merchant_id":"81431","method":"qw","currency":"RUB","commission":"0.02","payer_details":"some details","custom_field":{"email":"admin@enot.io","id":"456","test":"1"}}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $response = $api->paymentInfo(4242, 123, 456);

        $this->assertInstanceOf(PaymentInfoResponse::class, $response);

        $this->assertEquals(1, $response->getMerchant());
        $this->assertEquals('success', $response->getStatus());
        $this->assertEquals(1.00, $response->getAmount());
        $this->assertEquals(0.98, $response->getCredited());
        $this->assertEquals(650611, $response->getIntId());
        $this->assertEquals(81431, $response->getMerchantId());
        $this->assertEquals('qw', $response->getMethod());
        $this->assertEquals('RUB', $response->getCurrency());
        $this->assertEquals(0.02, $response->getCommission());
        $this->assertEquals([
            'email' => 'admin@enot.io',
            'id' => '456',
            'test' => '1'
        ], $response->getCustomField());
        $this->assertEquals('some details', $response->getPayerDetails());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCreatedStatus()
    {
        $body = '{"merchant":1,"status":"created","amount":"1.00","credited":"0.98","intid":650611,"merchant_id":"81431","method":"qw","currency":"RUB","commission":"0.02","payer_details":"some details","custom_field":{"email":"admin@enot.io","id":"456","test":"1"}}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $response = $api->paymentInfo(4242, 123, 456);

        $this->assertInstanceOf(PaymentInfoResponse::class, $response);

        $this->assertTrue($response->isCreated());
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

        $response = $api->paymentInfo(4242, 123, 456);

        $this->assertInstanceOf(PaymentInfoResponse::class, $response);
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

        $response = $api->paymentInfo(4242, 123, 456);

        $this->assertInstanceOf(PaymentInfoResponse::class, $response);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testOrderNotFoundErrorResponse()
    {
        $body = '{"status":"error","message":"ORDER_NOT_FOUND"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setEmail('test@test.com')
            ->setApiKey('api key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $this->expectException(OrderNotFoundException::class);

        $response = $api->paymentInfo(4242, 123, 456);

        $this->assertInstanceOf(PaymentInfoResponse::class, $response);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testTransactionNumbersIsNotFilled()
    {
        $api = (new EnotIoApi());

        $this->expectException(InvalidArgumentException::class);

        $api->paymentInfo(4242);
    }
}
