<?php

namespace Tokenly\Insight;


use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Tokenly\Insight\Client;

/*
* InsightServiceProvider
*/
class InsightServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindConfig();

        $this->app->bind('Tokenly\Insight\Client', function($app) {
            $client = new Client(Config::get('insight.connection_string'));
            return $client;
        });
    }

    protected function bindConfig()
    {

        // simple config
        $config = [
            'insight.connection_string' => env('INSIGHT_CONNECTION_STRING', 'http://localhost:3000'),
        ];

        // set the laravel config
        Config::set($config);
    }

}
