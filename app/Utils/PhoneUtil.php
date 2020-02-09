<?php

namespace App\Utils;

class PhoneUtil
{
    // +7 (900) 111-22-33 => 79001112233
    public static function clean($phone)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        if ($phone && $phone[0] != '7') {
            $phone = '7' . $phone;
        }
        return $phone;
    }

    // 79001112233 => +7 (900) 111-22-33
    public static function format($phone)
    {
        if ($phone[0] == '+') {
            return $phone;
        }

        $cuts = [1, 3, 3, 2, 2];

        $i = 0;
        $parts = [];
        foreach ($cuts as $cut) {
            $parts[] = mb_strimwidth($phone, $i, $cut);
            $i += $cut;
        }

        return "+$parts[0] ({$parts[1]}) {$parts[2]}-{$parts[3]}-{$parts[4]}";
    }

    public static function filter($phones)
    {
        return array_filter($phones, function ($e) {
            return !empty($e['phone']);
        });
    }
}
