<?php

namespace Larathereum\Types;

use Larathereum\Facades\Util;
use Larathereum\Validators\AddressValidator;

class TransactionInfo
{
    private $blockHash;
    private $blockNumber;
    private $from;
    private $to;
    private $contractAddress;
    private $gas;
    private $gasPrice;
    private $hash;
    private $input;
    private $nonce;
    private $transactionIndex;
    private $value;
    private $v;
    private $r;
    private $s;

    public function __construct(array $response)
    {
        $this->blockHash = new BlockHash($response['blockHash']);
        $this->blockNumber = hexdec($response['blockNumber']);
        $this->from = new Address($response['from']);
        if ($response['to']) {
            $this->to = new Address($response['to']);
        }

        $this->contractAddress = null;
        $this->gas = hexdec($response['gas']);
        $this->gasPrice = new Wei(hexdec($response['gasPrice']));
        $this->hash = new TransactionHash($response['hash']);
        $this->input = $response['input'];
        $this->nonce = hexdec($response['nonce']);
        $this->transactionIndex = hexdec($response['transactionIndex']);
        $this->value = new Wei(hexdec($response['value']));
        $this->v = $response['v'];
        $this->r = $response['r'];
        $this->s = $response['s'];

        $input = Util::decodeInput($response['input']);
        if (!empty($input)) {
            if ($input[0] == 'transfer') {
                $this->contractAddress = new ContractAddress($this->to);
                if (AddressValidator::validate($input[1])) {
                    $this->to = new Address($input[1]);
                }
                if ($input[2]->toString() > 0) {
                    $this->value = new Wei($input[2]);
                }

            } elseif ($input[0] == 'signedTransfer') {
                $this->contractAddress = new ContractAddress($this->to);
                if (AddressValidator::validate($input[1])) {
                    $this->from = new Address($input[1]);
                }

                if (AddressValidator::validate($input[2])) {
                    $this->to = new Address($input[2]);
                }
                if ($input[3]->toString() > 0) {
                    $this->value = new Wei($input[3]);
                }
            }
        }
    }

    public function blockHash(): BlockHash
    {
        return $this->blockHash;
    }

    public function blockNumber(): int
    {
        return $this->blockNumber;
    }

    public function from(): Address
    {
        return $this->from;
    }

    public function to(): ?Address
    {
        return $this->to;
    }

    public function contractAddress(): ?ContractAddress
    {
        return $this->contractAddress;
    }

    public function gas(): int
    {
        return $this->gas;
    }

    public function gasPrice(): Wei
    {
        return $this->gasPrice;
    }

    public function hash(): TransactionHash
    {
        return $this->hash;
    }

    public function input(): string
    {
        return $this->input;
    }

    public function nonce(): int
    {
        return $this->nonce;
    }

    public function transactionIndex(): int
    {
        return $this->transactionIndex;
    }

    public function value(): Wei
    {
        return $this->value;
    }

    public function v(): string
    {
        return $this->v;
    }

    public function r(): string
    {
        return $this->r;
    }

    public function s(): string
    {
        return $this->s;
    }
}
