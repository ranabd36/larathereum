<?php

namespace Larathereum\Methods;

use Larathereum\Types\Address;
use Larathereum\Types\Hash;
use Larathereum\Types\Transaction;
use Larathereum\Types\TransactionHash;

class PersonalClient extends AbstractMethods
{
    /**
     * @return Address[]
     */
    public function listAccounts(): array
    {
        $addresses = [];
        $response = $this->client->send(
            $this->client->request(67, 'personal_listAccounts', [])
        );

        if (!$response->getRpcResult()) {
            return $addresses;
        }
        foreach ($response->getRpcResult() as $address) {
            $addresses[] = new Address($address);
        }

        return $addresses;
    }

    public function newAccount(string $password): Address
    {
        $response = $this->client->send(
            $this->client->request(67, 'personal_newAccount', [$password])
        );

        return new Address($response->getRpcResult());
    }

    public function unlockAccount(Address $address, string $password, int $duration): bool
    {
        $response = $this->client->send(
            $this->client->request(67, 'personal_unlockAccount', [$address->toString(), $password, $duration])
        );
        $result = $response->getRpcResult();
        return empty($result) ? false : true;
    }

    public function sendTransaction(Transaction $transaction, string $password): TransactionHash
    {
        $response = $this->client->send(
            $this->client->request(1, 'personal_sendTransaction', [$transaction->toArray(), $password])
        );
        return new TransactionHash($response->getRpcResult());
    }

    public function sign(string $message, Address $address, string $password): string
    {
        $response = $this->client->send(
            $this->client->request(1, 'personal_sign', [$message, $address->toString(), $password])
        );
        return $response->getRpcResult();
    }
    
    public function ecRecover(string $message, string $signature): TransactionHash
    {
        $response = $this->client->send(
            $this->client->request(1, 'personal_ecRecover', [$message, $signature])
        );
        return new Address($response->getRpcResult());
    }
}
