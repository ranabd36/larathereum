<?php

namespace Larathereum\Methods;

use InvalidArgumentException;
use Larathereum\Contracts\Ethabi;
use Larathereum\Contracts\Types\Address;
use Larathereum\Contracts\Types\Boolean;
use Larathereum\Contracts\Types\Bytes;
use Larathereum\Contracts\Types\DynamicBytes;
use Larathereum\Contracts\Types\Integer;
use Larathereum\Contracts\Types\Str;
use Larathereum\Contracts\Types\Uinteger;
use Larathereum\Facades\Eth;
use Larathereum\Facades\Util;
use Larathereum\Formatters\AddressFormatter;
use Larathereum\Types\BlockNumber;
use Larathereum\Types\Transaction;
use Larathereum\Validators\AddressValidator;

class ContractClient extends AbstractMethods
{
    /**
     * client
     *
     */
    protected $client;

    /**
     * abi
     *
     * @var array
     */
    protected $abi;

    /**
     * constructor
     *
     * @var array
     */
    protected $constructor = [];

    /**
     * functions
     *
     * @var array
     */
    protected $functions = [];


    protected $function = '';

    /**
     * events
     *
     * @var array
     */
    protected $events = [];

    /**
     * toAddress
     *
     * @var string
     */
    protected $toAddress;

    /**
     * bytecode
     *
     * @var string
     */
    protected $bytecode;

    /**
     * eth
     *
     * @var Eth
     */
    protected $eth;

    /**
     * ethabi
     *
     * @var Ethabi
     */
    protected $ethabi;

    public function __construct($client)
    {
        @parent::__construct($client);

        $this->ethabi = new Ethabi([
            'address' => new Address,
            'bool' => new Boolean,
            'bytes' => new Bytes,
            'dynamicBytes' => new DynamicBytes,
            'int' => new Integer,
            'string' => new Str,
            'uint' => new Uinteger,
        ]);
    }

    /**
     * getFunctions
     *
     * @return array
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * getEvents
     *
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return string
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * setToAddress
     *
     * @param $address
     * @return $this
     */
    public function setToAddress($address)
    {
        return $this->at($address);
    }

    /**
     * at
     *
     * @param string $address
     * @return $this
     */
    public function at($address)
    {
        if (AddressValidator::validate($address) === false) {
            throw new InvalidArgumentException('Please make sure address is valid.');
        }
        $this->toAddress = AddressFormatter::format($address);

        return $this;
    }

    /**
     * getConstructor
     *
     * @return array
     */
    public function getConstructor()
    {
        return $this->constructor;
    }

    /**
     * getAbi
     *
     * @return array
     */
    public function getAbi()
    {
        return $this->abi;
    }

    /**
     * setAbi
     *
     * @param string $abi
     * @return $this
     */
    public function setAbi($abi)
    {
        return $this->abi($abi);
    }

    /**
     * abi
     *
     * @param string $abi
     * @return $this
     */
    public function abi($abi)
    {
        $abiArray = [];
        if (is_string($abi)) {
            $abiArray = json_decode($abi, true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new InvalidArgumentException('abi decode error: ' . json_last_error_msg());
            }
        } else {
            $abiArray = Util::jsonToArray($abi);
        }

        foreach ($abiArray as $item) {
            if (isset($item['type'])) {
                if ($item['type'] === 'function') {
                    $this->functions[] = $item;
                } elseif ($item['type'] === 'constructor') {
                    $this->constructor = $item;
                } elseif ($item['type'] === 'event') {
                    $this->events[$item['name']] = $item;
                }
            }
        }
        $this->abi = $abiArray;

        return $this;
    }

    /**
     * getEthabi
     *
     * @return array
     */
    public function getEthabi()
    {
        return $this->ethabi;
    }

    /**
     * getEth
     *
     * @return \Web3\Eth
     */
    public function getEth()
    {
        return $this->eth;
    }

    /**
     * setBytecode
     *
     * @param string $bytecode
     * @return $this
     */
    public function setBytecode($bytecode)
    {
        return $this->bytecode($bytecode);
    }

    /**
     * bytecode
     *
     * @param string $bytecode
     * @return $this
     */
    public function bytecode($bytecode)
    {
        if (HexValidator::validate($bytecode) === false) {
            throw new InvalidArgumentException('Please make sure bytecode is valid.');
        }
        $this->bytecode = Util::stripZero($bytecode);

        return $this;
    }

    /**
     * new
     * Deploy a contruct with params.
     *
     * @param mixed
     * @return void
     */
    public function new()
    {
        if (isset($this->constructor)) {
            $constructor = $this->constructor;
            $arguments = func_get_args();

            $input_count = isset($constructor['inputs']) ? count($constructor['inputs']) : 0;
            if (count($arguments) < $input_count) {
                throw new InvalidArgumentException('Please make sure you have put all constructor params and callback.');
            }

            if (!isset($this->bytecode)) {
                throw new InvalidArgumentException('Please call bytecode($bytecode) before new().');
            }
            $params = array_splice($arguments, 0, $input_count);
            $data = $this->ethabi->encodeParameters($constructor, $params);

            $transaction = new Transaction();

            if (count($arguments) > 0 && $arguments[0] instanceof Transaction) {
                $transaction = $arguments[0];
            }

            $transaction->data = '0x' . $this->bytecode . Util::stripZero($data);

            return Eth::sendTransaction($transaction);
        }
    }

    /**
     * send
     * Send function method.
     *
     * @param mixed
     * @return void
     */
    public function send()
    {
        if (isset($this->functions)) {
            $arguments = func_get_args();
            $method = array_splice($arguments, 0, 1)[0];

            if (!is_string($method)) {
                throw new InvalidArgumentException('Please make sure the method is string.');
            }

            $transaction = $this->getTransaction($arguments, $method);

            return Eth::sendTransaction($transaction);
        }
    }

    /**
     * call
     * Call function method.
     *
     * @param mixed
     * @return void
     */
    public function call()
    {
        if (isset($this->functions)) {
            $arguments = func_get_args();
            $method = array_splice($arguments, 0, 1)[0];

            if (!is_string($method)) {
                throw new InvalidArgumentException('Please make sure the method is string.');
            }

            $transaction = $this->getTransaction($arguments, $method);
            $response =  Eth::call($transaction, new BlockNumber());
            return current($this->ethabi->decodeParameters($this->function, $response));
        }
        return null;
    }

    protected function getTransaction($arguments, $method)
    {
        $functions = [];
        foreach ($this->functions as $function) {
            if ($function["name"] === $method) {
                $functions[] = $function;
                $this->function = $function;
            }
        };
        if (count($functions) < 1) {
            throw new InvalidArgumentException('Please make sure the method exists.');
        }

        // check the last one in arguments is transaction object
        $argsLen = count($arguments);
        $transaction = new Transaction();
        if ($argsLen > 0 && $arguments[$argsLen - 1] instanceof Transaction) {
            $transaction = $arguments[$argsLen - 1];
            $argsLen -= 1;
        }

        $params = [];
        $data = "";
        $functionName = "";

        foreach ($functions as $function) {
            if ($argsLen !== count($function['inputs'])) {
                continue;
            } else {
                $paramsLen = $argsLen;
            }

            try {
                $params = array_splice($arguments, 0, $paramsLen);
                $data = $this->ethabi->encodeParameters($function, $params);
                $functionName = Util::jsonMethodToString($function);
            } catch (InvalidArgumentException $e) {
                continue;
            }
            break;
        }
        if (empty($data) || empty($functionName)) {
            throw new InvalidArgumentException('Please make sure you have put all function params and callback.');
        }
        $functionSignature = $this->ethabi->encodeFunctionSignature($functionName);

        $transaction->to = new \Larathereum\Types\Address($this->toAddress);
        $transaction->data = $functionSignature . Util::stripZero($data);
        return $transaction;
    }

    /**
     * estimateGas
     * Estimate function gas.
     *
     * @param mixed
     * @return mixed|null
     */
    public function estimateGas()
    {
        if (isset($this->functions) || isset($this->constructor)) {
            $arguments = func_get_args();

            if (empty($this->toAddress) && !empty($this->bytecode)) {
                $constructor = $this->constructor;

                if (count($arguments) < count($constructor['inputs'])) {
                    throw new InvalidArgumentException('Please make sure you have put all constructor params and callback.');
                }

                if (!isset($this->bytecode)) {
                    throw new InvalidArgumentException('Please call bytecode($bytecode) before estimateGas().');
                }
                $params = array_splice($arguments, 0, count($constructor['inputs']));
                $data = $this->ethabi->encodeParameters($constructor, $params);
                $transaction = [];

                if (count($arguments) > 0 && $arguments[count($arguments) - 1] instanceof Transaction) {
                    $transaction = $arguments[count($arguments) - 1];
                }

                $transaction->data = '0x' . $this->bytecode . Util::stripZero($data);
            } else {
                $method = array_splice($arguments, 0, 1)[0];

                if (!is_string($method)) {
                    throw new InvalidArgumentException('Please make sure the method is string.');
                }
                $transaction = $this->getTransaction($arguments, $method);
            }

            return Eth::estimateGas($transaction);
        }
        return null;
    }

    /**
     * getData
     * @param mixed
     * @return void
     */
    public function getData()
    {
        if (isset($this->functions) || isset($this->constructor)) {
            $arguments = func_get_args();
            $functionData = '';

            if (empty($this->toAddress) && !empty($this->bytecode)) {
                $constructor = $this->constructor;

                if (count($arguments) < count($constructor['inputs'])) {
                    throw new InvalidArgumentException('Please make sure you have put all constructor params and callback.');
                }
                if (!isset($this->bytecode)) {
                    throw new InvalidArgumentException('Please call bytecode($bytecode) before getData().');
                }
                $params = array_splice($arguments, 0, count($constructor['inputs']));
                $data = $this->ethabi->encodeParameters($constructor, $params);
                $functionData = '0x' . $this->bytecode . Util::stripZero($data);
            } else {
                $method = array_splice($arguments, 0, 1)[0];

                if (!is_string($method)) {
                    throw new InvalidArgumentException('Please make sure the method is string.');
                }

                $functions = [];
                foreach ($this->functions as $function) {
                    if ($function["name"] === $method) {
                        $functions[] = $function;
                    }
                };
                if (count($functions) < 1) {
                    throw new InvalidArgumentException('Please make sure the method exists.');
                }

                $params = $arguments;
                $data = "";
                $functionName = "";
                foreach ($functions as $function) {
                    if (count($arguments) !== count($function['inputs'])) {
                        continue;
                    }
                    try {
                        $data = $this->ethabi->encodeParameters($function, $params);
                        $functionName = Util::jsonMethodToString($function);
                    } catch (InvalidArgumentException $e) {
                        continue;
                    }
                    break;
                }
                if (empty($data) || empty($functionName)) {
                    throw new InvalidArgumentException('Please make sure you have put all function params and callback.');
                }
                $functionSignature = $this->ethabi->encodeFunctionSignature($functionName);
                $functionData = $functionSignature . Util::stripZero($data);
            }
            return $functionData;
        }
    }
}
