<?php

declare(strict_types=1);

namespace dovbysh\Ip;

use dovbysh\Ip\Exception\IpRangeError;
use dovbysh\Ip\Exception\NotIp4;

class Range
{

    public function isIpInRange(string $ip, string $IpRange): bool
    {
        $longIp = ip2long($ip);
        if ($longIp === false) {
            throw new NotIp4('Ip is not ip4');
        }

        $m = [];
        $r = preg_match('~(\d+\.\d+\.\d+\.\d+)\/(\d+)~', $IpRange, $m);
        if ($r === false || $r === 0 || empty($m[1]) || !isset($m[2])) {
            throw new IpRangeError('Mask error');
        }

        $network = ip2long($m[1]);
        if ($network === false) {
            throw new IpRangeError('Mask error: not ip');
        }

        $d = intval($m[2]);
        if ($d < 0 || $d > 32) {
            throw new IpRangeError('Mask error');
        }

        $mask = bindec(str_repeat('1', $d) . str_repeat('0', 32 - $d));
        $networkMin = $network & $mask;
        $networkMax = $networkMin + bindec(str_repeat('1', 32 - $d));

        return $longIp >= $networkMin && $longIp <= $networkMax;
    }
}
