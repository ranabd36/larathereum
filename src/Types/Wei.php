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
        return bcdiv($this->amount, "1000000000000000000", $scale);
    }

    public function toString()
    {
        return $this->amount;
    }
}
