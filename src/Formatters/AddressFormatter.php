<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Larathereum\Formatters;

use Larathereum\Methods\Util;

class AddressFormatter implements IFormatter
{
    /**
     * format
     * to do: iban
     *
     * @param mixed $value
     * @return string
     */
    public static function format($value)
    {
        $value = (string)$value;

        if (Util::isAddress($value)) {
            $value = mb_strtolower($value);

            if (Util::isZeroPrefixed($value)) {
                return $value;
            }
            return '0x' . $value;
        }
        $value = IntegerFormatter::format($value, 40);

        return '0x' . $value;
    }
}