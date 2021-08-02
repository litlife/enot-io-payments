<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use Litlife\EnotIoPayments\EnotIoApi;
use PHPUnit\Framework\TestCase;

class EnotIoApiAllowedPayoutServicesTest extends TestCase
{
    public function testSetAndGet()
    {
        $api = (new EnotIoApi())
            ->setAllowedPayoutServices(['qw', 'cd']);

        $this->assertEquals(['qw', 'cd'], $api->getAllowedPayoutServices());
    }
}
