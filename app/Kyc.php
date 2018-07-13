<?php

namespace App;

use App\User;

class Kyc
{
    const STATUS_UNTOUCHED = 0;
    const STATUS_VERIFIED = 1;
    const STATUS_AUTO_FAILED = 2;
    const STATUS_MANUAL_FAILED = 3;
    const VERIFIED_STATUS_CODES = [1, 2];

    private $apiKey;
    private $kycBaseUrl;
    private $kycTokenUrl;
    private $kycStartUrl;
    private $httpClient;
   
    private $defaultRejectionReason = 'KYC verification not passed. Repeat verification or contact SWACE support.';
    private $rejectionReasons = [
        1 => 'Missing document photo. Please include document photo that contains your photo and general information.',
        2 => 'Document not accepted. Please use a nationally issued identity document with your photo and MRZ parameters (ID card or passport)',
        3 => 'Data in the document does not match data in the application. Please make sure you entered data correctly',
        4 => 'Name, nationality and date of birth was matched in at least one of the sanctions lists. Contact KYC provider for assistance.',
        5 => 'Missing individual’s photo/video selfie. Please submit your photo/video selfie.',
        6 => 'Individual’s face is the selfie does not match the face in the document. Please make sure you used your own document.',
        7 => 'Poor photo quality. Please use a better camera to submit your photos.',
        8 => 'Poor lighting, photo is too dark. Please make sure there is sufficient lighting when taking a photo.',
        9 => 'Blurred photo. Please, make sure that information in the document is clearly visible prior to submitting it.',
        10 => 'Face photo upload format. Uploaded face photo doesn\'t contain the passport/ID card and a note with the "Client Name" and upload date.',
        11 => 'Unidentified problem durin KYC verification. Please try again.'
    ];
    
    public function __construct()
    {
        $this->apiKey = env('KYC_API_KEY');
        $this->baseUrl = 'https://kycico.finpass.eu';
        $this->tokenApiUrl = $this->baseUrl . '/api/startsession';
        $this->dataApiUrl = $this->baseUrl . '/api/getdata';
        $this->statusApiUrl = $this->baseUrl . '/api/registrationStatus';
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
    
    public function getStatus(string $token) : int
    {
        return $this->getData($token)->status;
    }
    
    public function getReason(string $email) : string
    {
        $reason = $this->defaultRejectionReason;
        $reasonCode = $this->getRegistrationStatusCode($email);
        
        return $reasonCode && isset($this->rejectionReasons[$reasonCode]) ? $this->rejectionReasons[$reasonCode] : $reason;
    }
    
    public function getEmailByToken(string $token) : string {
        return $this->getData($token)->data->email;
    }
    
    private function getToken() : string
    {
        $data = [
            'apikey' => $this->apiKey,
            'data' => [
                'email' => auth()->user()->email
            ]
        ];
        
        return $this->sendJsonRequest($this->tokenApiUrl, $data)->token;
    }
    
    private function getData(string $token) : object
    {
        $data = [
            'apikey' => $this->apiKey,
            'token' => $token
        ];
        
        return $this->sendJsonRequest($this->dataApiUrl, $data);
    }
    
    private function getRegistrationStatusCode(string $email) : ?int
    {
        $data = [
            'apikey' => $this->apiKey,
            'email' => $email
        ];
        
        return $this->sendJsonRequest($this->statusApiUrl, $data)->RejectReasonId;
    }
    
    private function sendJsonRequest(string $url, array $data) : ?object
    {
        $response = $this->httpClient->post($url, [
            'body' => json_encode($data)
        ]);
        
        return json_decode($response->getBody()->getContents());
    }
}
            