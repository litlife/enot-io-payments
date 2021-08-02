<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use Litlife\EnotIoPayments\EnotIoApi;
use PHPUnit\Framework\TestCase;

class EnotIoApiGetSignature2Test extends TestCase
{
    public function test()
    {
        $api = new EnotIoApi();

        $signature = $api->getSignature2(4243, 42.3, 'secret', 23);

        $this->assertEquals('fe60a0d383c2f5c1585efd9d102f81d3', $signature);
    }
}
