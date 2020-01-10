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
        
        if ($this->_authCredential['mode'] == 'production')
        {
            $this->_constants = new Constant(true);
        }
        else
        {
            $this->_constants = new Constant(false);
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
                throw new Exception('Incorrect Transaction Request Body Provided');
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

    public function executeTransactionDetailsRequest($transactionId, $executeWithANewToken = false)
    {
        //Check if the token is present in the global variable or when executeWithANewToken is true
        if ($this->_bearerToken == null || $executeWithANewToken == true)
        {
            $this->_bearerToken = $this->executeBearerTokeRequest();
        }

        try {
            // Set the Http request headers properties
            $this->setHttpRequestHeaders();

             // Make request and store the transaction resposne in a variable
             $response = $this->_httpClient->get($this->_constants->transactionUrl . '/' . $transactionId);

            // Get the transaction response from Xente API
            $transactionProcessingResponse = json_decode($response->getBody()->getContents(), $assoc = true);
            return $transactionProcessingResponse;

        } catch (Exception $ex) {
            if ($this->_bearerToken != null && strpos($ex->getMessage(), '401') !== false)
            {
                // Repeat the execution but with a new bearer token
                $transactionDetailsResultsWithNewToken = $this->executeTransactionDetailsRequest($transactionId, true);
                    return $transactionDetailsResultsWithNewToken;
                }
            else if (strpos($ex->getMessage(), '400') !== false)
            {
                // Incorrect Transaction ID is provided (400)
                throw new Exception('Incorrect Transaction ID Provided');
            }
            else
            {
                // Any other exception during the Http Request
                throw new Exception($ex->getMessage());
            }
        }
    }

    public function executeTransactionDetailsRequest2($requestId, $executeWithANewToken = false)
    {
        //Check if the token is present in the global variable or when executeWithANewToken is true
        if ($this->_bearerToken == null || $executeWithANewToken == true)
        {
            $this->_bearerToken = $this->executeBearerTokeRequest();
        }

        try {
            // Set the Http request headers properties
            $this->setHttpRequestHeaders();

            // Make request and store the transaction response in a variable
            $response = $this->_httpClient->get($this->_constants->transactionUrl . '/Requests/' . $requestId);

            // Get the transaction response from Xente API
            $transactionProcessingResponse = json_decode($response->getBody()->getContents(), $assoc = true);
            return $transactionProcessingResponse;

        } catch (Exception $ex) {
            if ($this->_bearerToken != null && strpos($ex->getMessage(), '401') !== false)
            {
                // Repeat the execution but with a new bearer token
                $transactionDetailsResultsWithNewToken = $this->executeTransactionDetailsRequest2($requestId, true);
                return $transactionDetailsResultsWithNewToken;
            }
            else if (strpos($ex->getMessage(), '400') !== false)
            {
                // Incorrect Transaction ID is provided (400)
                throw new Exception('Incorrect Request ID Provided');
            }
            else
            {
                // Any other exception during the Http Request
                throw new Exception($ex->getMessage());
            }
        }
    }

    public function executeAccountDetailsRequest($accountId, $executeWithANewToken = false)
    {
        //Check if the token is present in the global variable or when executeWithANewToken is true
        if ($this->_bearerToken == null || $executeWithANewToken == true)
        {
            $this->_bearerToken = $this->executeBearerTokeRequest();
        }

        try {
            // Set the Http request headers properties
            $this->setHttpRequestHeaders();

            // Make request and store the account response in a variable
            $response = $this->_httpClient->get($this->_constants->accountUrl . '/' . $accountId);

            // Get the account response from Xente API
            $accountResponse = json_decode($response->getBody()->getContents(), $assoc = true);
            return $accountResponse;

        } catch (Exception $ex) {
            if ($this->_bearerToken != null && strpos($ex->getMessage(), '401') !== false)
            {
                // Repeat the execution but with a new bearer token
                $accountResponseWithNewToken = $this->executeAccountDetailsRequest($accountId, true);
                return $accountResponseWithNewToken;
            }
            else if (strpos($ex->getMessage(), '400') !== false)
            {
                // Incorrect Account ID is provided (400)
                throw new Exception('Incorrect account ID Provided');
            }
            else
            {
                // Any other exception during the Http Request
                throw new Exception($ex->getMessage());
            }
        }
    }

    public function executePaymentProvidersRequest($executeWithANewToken = false)
    {
        //Check if the token is present in the global variable or when executeWithANewToken is true
        if ($this->_bearerToken == null || $executeWithANewToken == true)
        {
            $this->_bearerToken = $this->executeBearerTokeRequest();
        }

        try {
            // Set the Http request headers properties
            $this->setHttpRequestHeaders();

            // Make request and store the payment providers response in a variable
            $response = $this->_httpClient->get($this->_constants->paymentProvidersUrl . '/MOBILEMONEYUG/providerItems?PageSize=10&PageNumber=1');

            // Get the transaction response from Xente API
            $paymentProvidersResponse = json_decode($response->getBody()->getContents(), $assoc = true);
            return $paymentProvidersResponse;

        } catch (Exception $ex) {
            if ($this->_bearerToken != null && strpos($ex->getMessage(), '401') !== false)
            {
                // Repeat the execution but with a new bearer token
                $paymentProviderResponseWithNewToken = $this->executePaymentProvidersRequest(true);
                return $paymentProviderResponseWithNewToken;
            }
            else
            {
                // Any other exception during the Http Request
                throw new Exception($ex->getMessage());
            }
        }
    }
}