<?php

namespace Larathereum\Types;

class Wei
{
    private $amount;

    public function __construct($amount)
    {
        $this->amount = (string)$amount;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function toEther($scale = 18): string
    {
        return bcdiv($this->amount, str_pad('1', $scale + 1, '0', STR_PAD_RIGHT), $scale);
    }

    public function toString()
    {
        return $this->amount;
    }
}
