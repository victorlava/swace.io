<?php

namespace App;

use App\User;

class Kyc
{
    private $apiKey;
    private $kycBaseUrl;
    private $kycTokenUrl;
    private $kycStartUrl;
    private $httpClient;
    private $verifiedStatusCodes = [1, 2];
    
    public function __construct()
    {
        $this->apiKey = 'a9eff2dad6cfc3ca38e60a40764c9a2eddee38fc5ac29a4a84f3e78e9abc99d5';
        $this->baseUrl = 'https://kycico.finpass.eu';
        $this->tokenApiUrl = $this->baseUrl . '/api/startsession';
        $this->dataApiUrl = $this->baseUrl . '/api/getdata';
        $this->startUrl = $this->baseUrl . '/Home/Start?token=';
        
        $this->httpClient =  new \GuzzleHttp\Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
            
    }
        
    public function getUrl() : string
    {
        $token = $this->getToken();
        $url = $this->startUrl . $token;

        return $url;
    }

    public function checkStatus(string $token) : bool
    {
        $statusCode = $this->getData($token)->status;

        return in_array($statusCode, $this->verifiedStatusCodes);
    }

    public function getVerifiedUserByToken(string $token) : ?User {
        $data = $this->getData($token);
        
        if(in_array($data->status, $this->verifiedStatusCodes)) {
            return User::whereEmail($data->data->email)->first();
        }

        return null;
    }
    
    private function getToken() : string
    {
        $response = $this->httpClient->post($this->tokenApiUrl, [
            'body' => json_encode([
                'apikey' => $this->apiKey,
                'data' => [
                    'email' => 'adasdasdasd'
                ]
            ])
        ]);
            
        return json_decode($response->getBody()->getContents())->token;
    }

    private function getData(string $token) : object
    {
        $response = $this->httpClient->post($this->dataApiUrl, [
            'body' => json_encode([
                'apikey' => $this->apiKey,
                'token' => $token
            ])
        ]);
            
        return json_decode($response->getBody()->getContents());
    }
}
        