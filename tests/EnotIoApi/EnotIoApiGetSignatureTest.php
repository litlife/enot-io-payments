<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use Litlife\EnotIoPayments\EnotIoApi;
use PHPUnit\Framework\TestCase;

class EnotIoApiGetSignatureTest extends TestCase
{
    public function test()
    {
        $api = new EnotIoApi();

        $signature = $api->getSignature(4242, 42.2, 'secret', 24);

        $this->assertEquals('be5ec29ea2ec635efd4824facaac78bd', $signature);
    }
}
