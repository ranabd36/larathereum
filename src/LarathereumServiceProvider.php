<?php

namespace Larathereum;

use Graze\GuzzleHttp\JsonRpc\Client;
use Illuminate\Support\ServiceProvider;
use Larathereum\Methods\ContractClient;
use Larathereum\Methods\EthClient;
use Larathereum\Methods\NetClient;
use Larathereum\Methods\PersonalClient;
use Larathereum\Methods\ShhClient;
use Larathereum\Methods\Util;
use Larathereum\Methods\Web3Client;

class LarathereumServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('larathereum.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'larathereum');


        // Register the main class to use with the facade
        $this->app->singleton('eth', function () {
            return new EthClient($this->getClient());
        });

        $this->app->singleton('net', function () {
            return new NetClient($this->getClient());
        });

        $this->app->singleton('personal', function () {
            return new PersonalClient($this->getClient());
        });

        $this->app->singleton('shh', function () {
            return new ShhClient($this->getClient());
        });

        $this->app->singleton('util', function () {
            return new Util();
        });

        $this->app->singleton('web3', function () {
            return new Web3Client($this->getClient());
        });

        $this->app->singleton('contract', function () {
            return new ContractClient($this->getClient());
        });

        $this->app->alias('eth', EthClient::class);
        $this->app->alias('net', NetClient::class);
        $this->app->alias('personal', PersonalClient::class);
        $this->app->alias('shh', PersonalClient::class);
        $this->app->alias('util', Util::class);
        $this->app->alias('web3', Web3Client::class);
        $this->app->alias('contract', ContractClient::class);


    }

    private function getClient()
    {
        $url = $this->app['config']['larathereum']['url'];
        return Client::factory($url);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['eth', 'net', 'personal', 'shh', 'util', 'web3','contract', EthClient::class, NetClient::class, PersonalClient::class, ShhClient::class, Util::class, Web3Client::class, ContractClient::class];
    }

}
