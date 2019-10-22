<?php

namespace Larathereum\Types;

class Wei
{
    private $amount;

    public function __construct($amount)
    {
        $this->amount = $this->format($amount);
    }

    private function format($amount)
    {
        $amount = (string)$amount;
        if (preg_match('/e|E/', $amount, $match)) {
            $comps = explode($match[0], $amount);
            if (count($comps) > 2 || count($comps) < 2 ) {
                throw new \Exception('Invalid scientific notation.');
            }

            return str_pad(intval($comps[0]), $comps[1], '0', STR_PAD_RIGHT);
        }

        return $amount;
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
