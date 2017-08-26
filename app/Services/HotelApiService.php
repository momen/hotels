<?php

namespace App\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class HotelApiService {

    protected $url;
    public $client;
    public $result;
    private $method;

    /**
     * Object Initialization
     */
    public function __construct() {
        $this->client = new Client();
        $this->url = config('apiConfig.url.url');
        $this->method = config('apiConfig.url.method');
    }

    /**
     * Returing the API result.
     * 
     * @return json
     */
    private function result() {
        return json_decode($this->result, true);
    }

    /**
     * Hitting the Guzzle GET & POST method.
     * 
     * @param array $params 
     */
    private function prepareRequest($params) {
        if (strtoupper($this->method) == 'GET') {
            $request = $this->client->get($this->url, [
                'query' => $params
            ]);
        } else {
            $request = $this->client->post($this->url, [
                'form_params' => [
                    'JSON' => json_encode($params)
                ]
            ]);
        }
        return $request;
    }

    /**
     * Make request function decide POST/GET method.
     * 
     * @param array $params
     * @return json
     */
    public function makeRequest($params = array()) {
        try {
            $request = $this->prepareRequest($params);
            $statusCode = $request->getStatusCode();
            if (( $statusCode >= 200 ) && ( $statusCode < 300)) {
                $this->result = $request->getBody();
            } else {
                $this->result = \GuzzleHttp\json_encode(['status' => 'error']);
            }
        } catch (GuzzleException $ex) {
            $this->result = \GuzzleHttp\json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
        }
        return $this->result();
    }

}

