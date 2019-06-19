<?php

namespace Larathereum\Types;

class Transaction
{
    public $from;
    public $to;
    public $data;
    public $gas;
    public $gasPrice;
    public $value;
    public $nonce;

    /**
     * Transaction constructor.
     * @param Address $from
     * @param Address $to
     * @param string|null $data
     * @param int|null $gas
     * @param int $gasPrice
     * @param int|string|null $value
     * @param int|null $nonce
     */
    public function __construct(
        Address $from = null,
        Address $to = null,
        string $data = null,
        int $gas = null,
        int $gasPrice = null,
        $value = null,
        int $nonce = null
    )
    {
        $this->from = $from;
        $this->to = $to;
        $this->data = $data;
        $this->gas = $gas;
        $this->gasPrice = $gasPrice;
        $this->value = $value;
        $this->nonce = $nonce;
    }

    public function toArray(): array
    {
        if (!is_null($this->from)) {
            $transaction['from'] = $this->from->toString();
        }

        if (!is_null($this->to)) {
            $transaction['to'] = $this->to->toString();
        }

        if (!is_null($this->data)) {
            $transaction['data'] = $this->data;
        }

        if (!is_null($this->gas)) {
            $transaction['gas'] = '0x' . dechex($this->gas);
        }

        if (!is_null($this->gasPrice)) {
            $transaction['gasPrice'] = '0x' . dechex($this->gasPrice);
        }

        if (!is_null($this->value)) {
            $transaction['value'] = '0x' . \Phlib\base_convert($this->value, 10, 16);
        }

        if (!is_null($this->nonce)) {
            $transaction['nonce'] = '0x' . dechex($this->nonce);
        }

        return $transaction;
    }
}
