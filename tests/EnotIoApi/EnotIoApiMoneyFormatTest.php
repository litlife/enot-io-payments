<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use Litlife\EnotIoPayments\EnotIoApi;
use PHPUnit\Framework\TestCase;

class EnotIoApiMoneyFormatTest extends TestCase
{
    public function test1()
    {
        $api = new EnotIoApi();

        $this->assertEquals('10.00', $api->moneyFormat('10'));
    }

    public function test2()
    {
        $api = new EnotIoApi();

        $this->assertEquals('10.00', $api->moneyFormat(10.00));
    }

    public function test3()
    {
        $api = new EnotIoApi();

        $this->assertEquals('10.00', $api->moneyFormat(10.0));
    }

    public function test4()
    {
        $api = new EnotIoApi();

        $this->assertEquals('1000000000.00', $api->moneyFormat(1000000000.0000));
    }
}
