<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use Litlife\EnotIoPayments\EnotIoApi;
use Litlife\EnotIoPayments\Exceptions\InvalidSignatureException;
use Litlife\EnotIoPayments\Requests\PaymentStatusRequest;
use PHPUnit\Framework\TestCase;

class EnotIoApiPaymentStatusTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccessfulRequest()
    {
        $params = [
            'merchant' => 150,
            'amount' => 200.00,
            'credited' => 196.00,
            'intid' => 1545855,
            'merchant_id' => 99,
            'method' => 'cd',
            'sign' => 'cd1d6b67f3335038656d9009ab4ecfa9',
            'sign_2' => 'b86410d16a20bb57366d29b0d884bcb2',
            'currency' => 'RUB',
            'commission' => 0.00,
            'payer_details' => '539175******7523',
            'custom_field' => [
                'email' => 'test@email.ru',
                'id_user' => '125454'
            ]
        ];

        $api = (new EnotIoApi())
            ->setSecretKey2('api key');

        $request = $api->paymentStatus($params);

        $this->assertInstanceOf(PaymentStatusRequest::class, $request);

        $this->assertEquals(150, $request->getMerchant());
        $this->assertEquals(200.00, $request->getAmount());
        $this->assertEquals(196.00, $request->getCredited());
        $this->assertEquals(1545855, $request->getIntId());
        $this->assertEquals('99', $request->getMerchantId());
        $this->assertEquals('cd', $request->getMethod());
        $this->assertEquals('RUB', $request->getCurrency());
        $this->assertEquals(0.00, $request->getCommission());
        $this->assertEquals('539175******7523', $request->getPayerDetails());
        $this->assertEquals([
            'email' => 'test@email.ru',
            'id_user' => '125454'
        ], $request->getCustomField());

    }

    /**
     * @throws \Exception
     */
    public function testSignatureInvalid()
    {
        $params = [
            'merchant' => 151,
            'amount' => 200.00,
            'credited' => 196.00,
            'intid' => 1545855,
            'merchant_id' => 99,
            'method' => 'cd',
            'sign' => 'cd1d6b67f3335038656d9009ab4ecfa9',
            'sign_2' => 'b86410d16a20bb57366d29b0d884bcb2',
            'currency' => 'RUB',
            'commission' => 0.00,
            'payer_details' => '539175******7523',
            'custom_field' => [
                'email' => 'test@email.ru',
                'id_user' => '125454'
            ]
        ];

        $api = (new EnotIoApi())
            ->setSecretKey2('api key');

        $this->expectException(InvalidSignatureException::class);

        $request = $api->paymentStatus($params);

        $this->assertInstanceOf(PaymentStatusRequest::class, $request);
    }

    /**
     * @throws \Exception
     */
    public function testGetParams()
    {
        $params = [
            'key' => 'value',
            'merchant' => 150,
            'amount' => 200.00,
            'credited' => 196.00,
            'intid' => 1545855,
            'merchant_id' => 99,
            'method' => 'cd',
            'sign' => 'cd1d6b67f3335038656d9009ab4ecfa9',
            'sign_2' => 'b86410d16a20bb57366d29b0d884bcb2',
            'currency' => 'RUB',
            'commission' => 0.00,
            'payer_details' => '539175******7523',
            'custom_field' => [
                'email' => 'test@email.ru',
                'id_user' => '125454'
            ]
        ];

        $api = (new EnotIoApi())
            ->setSecretKey2('api key');

        $request = $api->paymentStatus($params);

        unset($params['key']);

        $this->assertEquals($params, $request->getParams());
    }
}
