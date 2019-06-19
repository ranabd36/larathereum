<?php

/**
 * This file is part of web3.php package.
 *
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 *
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Larathereum\Validators;

class HexValidator
{
    /**
     * validate
     *
     * @param string $value
     * @return bool
     */
    public static function validate($value)
    {
        if (!is_string($value)) {
            return false;
        }
        return (preg_match('/^0x[a-fA-F0-9]*$/', $value) >= 1);
    }
}