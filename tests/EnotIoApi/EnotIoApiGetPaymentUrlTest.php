<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use Litlife\EnotIoPayments\EnotIoApi;
use PHPUnit\Framework\TestCase;

class EnotIoApiGetPaymentUrlTest extends TestCase
{
    public function testAllParameters()
    {
        $api = (new EnotIoApi())
            ->setUrl('https://enot.io')
            ->setMerchantId(424242)
            ->setSecretKey('secret key');

        $url = $api->getPaymentUrl(42.2, 4242, 'EUR',
            'Some comment',
            [
                'key' => 'value',
                'ping' => 'pong'
            ],
            'cd', 0, 'https://success.url', 'https://fail.url');

        $this->assertEquals(
            'https://enot.io/pay?m=424242&oa=42.2&o=4242&s=8a7d38fa09963aaadd32a8422fbfd397&cr=EUR&c=Some+comment&cf%5Bkey%5D=value&cf%5Bping%5D=pong&p=cd&ap=0&success_url=https%3A%2F%2Fsuccess.url&fail_url=https%3A%2F%2Ffail.url',
            $url);
    }
}
