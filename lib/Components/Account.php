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
        $accountDetailsResponse = $this->_httpRequestClient
                                       ->executeAccountDetailsRequest($accountId);
        return $accountDetailsResponse;
    }
}

