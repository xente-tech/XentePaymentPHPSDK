<?php

namespace XentePaymentSDK\Components;
use XentePaymentSDK\Services\Validation;
use XentePaymentSDK\Services\HttpRequestClient;
use Exception;

class Transaction
{
    private $_httpRequestClient;
    private $_authCredential;
    
    public function __construct($authCredential)
    {
        $this->_authCredential = $authCredential;
        $this->_httpRequestClient = HttpRequestClient::getInstance($this->_authCredential);
    }

    public function createTransaction($transactionRequest)
    {
        // Prepare the transactionRequest for Http request
        $transactionRequestArray = [
            'paymentProvider' => $transactionRequest['paymentProvider'],
            'amount' => $transactionRequest['amount'],
            'message' => $transactionRequest['message'],
            'customerId' => $transactionRequest['customerId'],
            'customerPhone' => $transactionRequest['customerPhone'],
            'customerEmail' => $transactionRequest['customerEmail'],
            'customerReference' => $transactionRequest['customerReference'],
            'batchId' => $transactionRequest['batchId'],
            'requestId' => $transactionRequest['requestId']
        ];

        // Add meta data information when it is provided
        if (array_key_exists('metadata', $transactionRequest))
        {
            $transactionRequestArray['metadata'] = $transactionRequest['metadata'];
        }

        try
        {
            $transactionProcessingResponse = $this->_httpRequestClient
                                                ->executeTransactionRequest($transactionRequestArray);
            return [
                'status' => 'success',
                'data' => $transactionProcessingResponse
            ];
        }
        catch(Exception $ex)
        {
            return [
                'status' => 'error',
                'data' => $ex->getMessage()
            ];
        }
    }

    public function getTransactionDetailsById($transactionId)
    {
        try
        {
            $transactionDetailsResponse = $this->_httpRequestClient
                                               ->executeBearerTokeRequest($transactionId);
            return [
                'status' => 'success',
                'data' => $transactionDetailsResponse
            ];
        }
        catch(Exception $ex)
        {
            return [
                'status' => 'error',
                'data' => $ex->getMessage()
            ];
        }
    }
}