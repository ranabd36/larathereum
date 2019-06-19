<?php

namespace Larathereum\Types;

class TransactionReceipt
{
    private $blockHash;
    private $blockNumber;
    private $contractAddress;
    private $cumulativeGasUsed;
    private $from;
    private $gasUsed;
    private $logs;
    private $logsBloom;
    private $status;
    private $to;
    private $transactionHash;
    private $transactionIndex;

    public function __construct($result)
    {
        $this->blockHash = new BlockHash($result['blockHash']);
        $this->blockNumber = hexdec($result['blockNumber']);
        if ($result['contractAddress']) {
            $this->contractAddress = new ContractAddress($result['contractAddress']);
        }

        $this->cumulativeGasUsed = hexdec($result['cumulativeGasUsed']);
        $this->from = new Address($result['from']);
        $this->gasUsed = hexdec($result['gasUsed']);
        $this->logs = $result['logs'];
        $this->logsBloom = $result['logsBloom'];
        $this->status = hexdec($result['status']);
        if ($result['to']) {
            $this->to = new Address($result['to']);
        }
        $this->transactionHash = new TransactionHash($result['transactionHash']);
        $this->transactionIndex = hexdec($result['transactionIndex']);
    }

    public function blockHash(): BlockHash
    {
        return $this->blockHash;
    }

    public function blockNumber(): int
    {
        return $this->blockNumber;
    }

    public function contractAddress(): ContractAddress
    {
        return $this->contractAddress;
    }

    public function cumulativeGasUsed(): int
    {
        return $this->cumulativeGasUsed;
    }

    public function from(): Address
    {
        return $this->from;
    }

    public function gasUsed(): int
    {
        return $this->gasUsed;
    }

    public function logs(): array
    {
        return $this->logs;
    }

    public function logsBloom(): string
    {
        return $this->logsBloom;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function to(): ?Address
    {
        return $this->to;
    }

    public function transactionHash(): TransactionHash
    {
        return $this->transactionHash;
    }

    public function transactionIndex(): int
    {
        return $this->transactionIndex;
    }
}
