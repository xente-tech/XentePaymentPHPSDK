<?php

namespace XentePaymentSDK\Components;

use XentePaymentSDK\Services\HttpRequestClient;

class Account
{
    private $_httpRequestClient;
    private $_authCredential;

    public function __construct($authCredential)
    {
        $this->_authCredential = $authCredential;
        $this->_httpRequestClient = HttpRequestClient::getInstance($this->_authCredential);
    }

    public function getAccountDetailsById($accountId)
    {
        try
        {
            $accountDetailsResponse = $this->_httpRequestClient
                ->executeAccountDetailsRequest($accountId);
            return [
                'status' => 'success',
                'data' => $accountDetailsResponse
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

