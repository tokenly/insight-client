<?php

namespace Tokenly\Insight;


use Exception;

/*
* Client
* A Counterparty client
*/
class Client
{

    public function __construct($connection_string) {
        $this->connection_string = $connection_string;
    }

    public function buildRequest($path, $params=[], \GuzzleHttp\Client $client=null) {
        // get client
        if ($client === null) { $client = $this->buildClient(); }

        return $client->createRequest('GET', '/api/'.ltrim($path, '/'), [
            'headers' => ['Content-Type' => 'application/json'],
        ]);
    }

    public function getBlock($block_hash) {
        return $this->callAPI('/block/'.$block_hash);
    }

    public function getTransaction($txid) {
        return $this->callAPI('/tx/'.$txid);
    }

    public function getUnspentTransactions($address) {
        // /api/addr/[:addr]/utxo[?noCache=1]
        return $this->callAPI('/addr/'.$address.'/utxo');
    }

    public function getBestBlockHash() {
        return $this->getStatus('getBestBlockHash');
    }

    public function getStatus($status_cmd) {
        return $this->callAPI('/status?q='.$status_cmd);
    }

    public function callAPI($path, $arguments=[]) {
        // get the client
        $client = $this->buildClient();

        // build the request
        $request = $this->buildRequest($path, $arguments ? $arguments[0] : [], $client);

        // get the response
        $response = $client->send($request);

        // return json data
        $json = $response->json();

        // check for error
        if (isset($json['error'])) {
            $error = $json['error']['message'];
            if (isset($json['error']['data'])) {
                $error .= "\n".json_encode($json['error']['data'], 192);
            }
            throw new Exception($error, $json['error']['code']);
        }

        // return JSON
        if ($json) { return $json; }

        throw new Exception("Unexpected Response: ".$response, 1);
    }




    protected function buildClient() {
        $client = new \GuzzleHttp\Client([
            'base_url' => $this->connection_string,
        ]);
        return $client;
    }
    

}

