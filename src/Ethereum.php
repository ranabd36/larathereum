<?php

namespace Larathereum;

use Graze\GuzzleHttp\JsonRpc\Client;
use Larathereum\Methods\ContractClient;
use Larathereum\Methods\EthClient;
use Larathereum\Methods\NetClient;
use Larathereum\Methods\PersonalClient;
use Larathereum\Methods\ShhClient;
use Larathereum\Methods\Util;
use Larathereum\Methods\Web3Client;

class Ethereum
{
    private $client;
    private $methods = [];
    private $abi = [];

    public function __construct(string $url)
    {
        $this->client = Client::factory($url);
        $this->methods = [
            'net' => new NetClient($this->client),
            'eth' => new EthClient($this->client),
            'shh' => new ShhClient($this->client),
            'web3' => new Web3Client($this->client),
            'personal' => new PersonalClient($this->client),
            'contract' => new ContractClient(),
            'utils' => new Util()
        ];
    }

    public function net(): NetClient
    {
        return $this->methods['net'];
    }

    public function web3(): Web3Client
    {
        return $this->methods['web3'];
    }

    public function shh(): ShhClient
    {
        return $this->methods['shh'];
    }

    public function eth(): EthClient
    {
        return $this->methods['eth'];
    }

    public function personal(): PersonalClient
    {
        return $this->methods['personal'];
    }

    public function contract(): ContractClient
    {
        return $this->methods['contract'];
    }

    public function utils(): Util
    {
        return $this->methods['utils'];
    }
}
