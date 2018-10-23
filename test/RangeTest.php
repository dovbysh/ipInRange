<?php

declare(strict_types=1);

namespace dovbysh\IpTest;


use dovbysh\Ip\Exception\IpRangeError;
use dovbysh\Ip\Exception\NotIp4;
use dovbysh\Ip\Range;
use PHPUnit\Framework\TestCase;

class RangeTest extends TestCase
{
    public function testNotIp4()
    {
        $a = new Range();

        $this->expectException(NotIp4::class);

        $a->isIpInRange('222', '');
    }

    public function testIpRangeEmpty()
    {
        $a = new Range();

        $this->expectException(IpRangeError::class);

        $a->isIpInRange('31.173.80.80', '');
    }

    public function testIpRangeNotIp()
    {
        $a = new Range();

        $this->expectException(IpRangeError::class);

        $a->isIpInRange('31.173.80.80', '625.0.0.0/0');
    }

    public function testIpRangeMaskError()
    {
        $a = new Range();

        $this->expectException(IpRangeError::class);

        $a->isIpInRange('31.173.80.80', '25.0.0.0/33');
    }

    /**
     * @dataProvider mainProvider
     */
    public function testMain(bool $expected, string $ip, string $IpRange)
    {
        $a = new Range();

        $this->assertSame($expected, $a->isIpInRange($ip, $IpRange));
    }

    public function mainProvider(): array
    {
        return [
            [true, '31.173.80.80', '31.173.80.0/21'],
            [false, '31.173.79.255', '31.173.80.0/21'],
            [true, '31.173.79.255', '31.173.79.0/21'],
            [true, '31.173.80.80', '31.173.80.80/32'],
        ];
    }
}