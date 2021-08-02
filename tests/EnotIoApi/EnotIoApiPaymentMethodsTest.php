<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Litlife\EnotIoPayments\EnotIoApi;
use Litlife\EnotIoPayments\Exceptions\MerchantNotFoundResponseException;
use Litlife\EnotIoPayments\Exceptions\NoPaymentMethodsEnabledException;
use Litlife\EnotIoPayments\Responses\PaymentMethodResponse;
use PHPUnit\Framework\TestCase;

class EnotIoApiPaymentMethodsTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessfulResponse()
    {
        $body = '{"status":"success","methods":{"qw":{"cm":"3.00"},"cd":{"cm":"6.00"},"ya":{"cm":"3.00"},"pa":{"cm":"4.00"},"pm":{"cm":"3.00"}}}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setMerchantId(42)
            ->setSecretKey('secret key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $response = $api->paymentMethods();

        $this->assertInstanceOf(PaymentMethodResponse::class, $response);

        $array = [
            'qw' => ['cm' => 3.00],
            'cd' => ['cm' => 6.00],
            'ya' => ['cm' => 3.00],
            'pa' => ['cm' => 4.00],
            'pm' => ['cm' => 3.00],
        ];

        $this->assertEquals($array, $response->getMethods());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyMethodsErrorResponse()
    {
        $body = '{"status":"error","methods":"empty"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setMerchantId(42)
            ->setSecretKey('secret key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $this->expectException(NoPaymentMethodsEnabledException::class);

        $response = $api->paymentMethods();

        $this->assertInstanceOf(PaymentMethodResponse::class, $response);

        $this->assertTrue($response->isError());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testMerchantNotFoundErrorResponse()
    {
        $body = '{"status":"error","message":"merchant_not_found"}';

        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);

        $handlerStack = HandlerStack::create($mock);

        $api = (new EnotIoApi())
            ->setMerchantId(42)
            ->setSecretKey('secret key')
            ->setHttpClientConfig(['handler' => $handlerStack]);

        $this->expectException(MerchantNotFoundResponseException::class);

        $response = $api->paymentMethods();

        $this->assertInstanceOf(PaymentMethodResponse::class, $response);

        $this->assertTrue($response->isError());
    }
}
