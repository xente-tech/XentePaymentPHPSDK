<?php

namespace XentePaymentSDK\Services;

use GuzzleHttp\Client;
use XentePaymentSDK\Services\Validation;
use XentePaymentSDK\Services\Constant;
use GuzzleHttp\Exception\RequestException;
use Exception;

class HttpRequestClient
{
    // Implementation of singleton pattern
    private static $_instance = null;

    public static function getInstance($authCredential)
    {
        if (self::$_instance == null)
        {
            self::$_instance = new HttpRequestClient($authCredential);
        }

        return self::$_instance;
    }


    // Properties of the class
    private $_bearerToken;
    private $_authCredential;
    private $_constants;
    private $_httpClient;

    private function __construct($authCredential)
    {
        // Initialize the authentication credentials
        $this->_authCredential = $authCredential;

        // Initialize Guzzle for making Http request
        $this->_httpClient = new Client();
        
        if ($this->_authCredential['mode'] == 'sandbox')
        {
            $this->_constants = new Constant(true);
        }
        else
        {
            $this->constants = new Constant(false);
        }
    }

     // Function that periodically resets the headers parameters in order avoid 403 forbidden
     private function setHttpRequestHeaders()
     {
         $this->_httpClient = new Client([
             'base_uri' => $this->_constants->baseUrl,
             'headers' => [
                 'Content-Type' => 'application/json',
                 'Accept' => 'application/json',
                 'X-ApiAuth-ApiKey' => $this->_authCredential['apikey'],
                 'X-Date' => date('Y-m-d\TH:i:sP'),
                 'X-Correlation-ID' => time(),
                 'Authorization' => 'Bearer ' . $this->_bearerToken
             ]
         ]);
     }

    public function executeBearerTokeRequest(){

        try
        {   // Set the Http request headers properties
            $this->setHttpRequestHeaders();

            // Make request and store the resposne in a variable
            $response = $this->_httpClient->post($this->_constants->authUrl,
                ['body' => json_encode(
                    [
                        "apikey" => $this->_authCredential['apikey'],
                        "password" => $this->_authCredential['password'],
                        "mode" => $this->_authCredential['mode']
                    ]
                )]
            );

            // $responseStatusCode = $response->getStatusCode();

            // Get the bearer token object from Xente payment API
            $bearerTokenResponse = json_decode($response->getBody()->getContents(), $assoc = true);

            // The global BearerToken property is updated when a new token is requested from the API
            $this->_bearerToken = $bearerTokenResponse['token'];
            return $this->_bearerToken;
        }
        catch(RequestException $ex)
        {
            // This exception occur when a user submitted in a wrong authentication credentials
            throw new Exception('Wrong Apikey or Password provided');
        }
        catch(Exception $ex)
        {
            throw new Exception($ex->getMessage());
        }
    }

    public function executeTransactionRequest($transactionRequest, $executeWithANewToken = false)
    {
        //Check if the token is present in the global variable or when executeWithANewToken is true
        if ($this->_bearerToken == null || $executeWithANewToken == true)
        {
            $this->_bearerToken = $this->executeBearerTokeRequest();
        }

        try
        {   // Set the Http request headers properties
            $this->setHttpRequestHeaders();

            // Make request and store the resposne in a variable
            $response = $this->_httpClient->post($this->_constants->transactionUrl,
                ['body' => json_encode(
                   $transactionRequest
                )]
            );

            $responseStatusCode = $response->getStatusCode();

            // Get the bearer token object from Xente payment API
            $transactionProcessingResponse = json_decode($response->getBody()->getContents(), $assoc = true);
            return $transactionProcessingResponse;
        }
        catch(RequestException $ex)
        {
            if ($this->_bearerToken != null && strpos($ex->getMessage(), '401') !== false) {
                // This exception occurs when the bearer token has expired from the global variable
                $responseWithANewToken = $this->executeTransactionRequest($transactionRequest, true);
                return $responseWithANewToken;
            }  
            else if(strpos($ex->getMessage(), '400') !== false)
            {
                // This occurs when user submitted invalid transaction request e.g. Duplicate RequestId
                throw new Exception('Invalid Transaction Request Provided');
            }
            else
            {
                // Any other exception during the Http Request
                throw new Exception($ex->getMessage());
            }
        }
        catch(Exception $ex)
        {
            throw new Exception($ex->getMessage());
        }

    }

    public function executeTransactionDetailsRequest($transactionId)
    {

    }

    public function executeAccountDetailsRequest($accountId)
    {

    }

    public function executePaymentProvidersRequest()
    {

    }
}