<?php

namespace Litlife\EnotIoPayments\Tests\EnotIoApi;

use Litlife\EnotIoPayments\EnotIoApi;
use PHPUnit\Framework\TestCase;

class EnotIoApiMoneyFormatTest extends TestCase
{
    public function test1()
    {
        $api = new EnotIoApi();

        $this->assertEquals('10', $api->moneyFormat(10));
    }

    public function test2()
    {
        $api = new EnotIoApi();

        $this->assertEquals('10.2', $api->moneyFormat(10.2));
    }

    public function test3()
    {
        $api = new EnotIoApi();

        $this->assertEquals('10.22', $api->moneyFormat(10.22));
    }

    public function test4()
    {
        $api = new EnotIoApi();

        $this->assertEquals('1000000000', $api->moneyFormat(1000000000.0000));
    }

    public function test5()
    {
        $api = new EnotIoApi();

        $this->assertEquals('10.2', $api->moneyFormat(10.20));
    }

    public function test6()
    {
        $api = new EnotIoApi();

        $this->assertEquals('10', $api->moneyFormat(10.00));
    }

    public function test7()
    {
        $api = new EnotIoApi();

        $this->assertEquals('20', $api->moneyFormat(20.00000));
    }
}
