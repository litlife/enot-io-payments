<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use Litlife\EnotIoPayments\EnotIoApi;
use Litlife\EnotIoPayments\Requests\PayoutStatusRequest;
use PHPUnit\Framework\TestCase;

class EnotIoApiPayoutStatusTest extends TestCase
{
    public function testSuccessfulRequest()
    {
        $params = [
            'status' => 'fail',
            'transaction_id' => 42,
            'orderid' => 24,
            'message' => 'Error message',
            'service' => 'cd',
            'wallet' => '79192131245',
            'sum' => 12.34,
            'commission' => '0.02'
        ];

        $api = (new EnotIoApi());

        $request = $api->payoutStatus($params);

        $this->assertInstanceOf(PayoutStatusRequest::class, $request);

        $this->assertEquals('fail', $request->getStatus());
        $this->assertEquals(42, $request->getTransactionId());
        $this->assertEquals(24, $request->getOrderId());
        $this->assertEquals('Error message', $request->getMessage());
        $this->assertEquals('cd', $request->getService());
        $this->assertEquals('79192131245', $request->getWallet());
        $this->assertEquals(12.34, $request->getSum());
        $this->assertEquals(0.02, $request->getCommission());
    }

    public function testGetParams()
    {
        $params = [
            'key' => '123',
            'status' => 'fail',
            'transaction_id' => 42,
            'orderid' => 24,
            'message' => 'Error message',
            'service' => 'cd',
            'wallet' => '79192131245',
            'sum' => 12.34,
            'commission' => '0.02'
        ];

        $api = (new EnotIoApi());

        $request = $api->payoutStatus($params);

        unset($params['key']);

        $this->assertEquals($params, $request->getParams());
    }
}
