<?php

namespace Tokenly\Insight;


use Exception;
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
        $this->package('tokenly/insight-client', 'insight-client', __DIR__.'/../../');

        $this->app->bind('Tokenly\Insight\Client', function($app) {
            $config = $app['config']['insight-client::insight'];
            $client = new Client($config['connection_string']);
            return $client;
        });
    }

}

