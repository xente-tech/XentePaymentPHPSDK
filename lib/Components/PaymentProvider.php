<?php

namespace XentePaymentSDK\Components;

use XentePaymentSDK\Services\HttpRequestClient;


class PaymentProvider
{
    // Private properties 
    private $_authCredential;
    private $_httpRequestClient;
    
    // The constructor initializes the private properties above
    public function __construct($authCredential)
    {
        $this->_authCredential = $authCredential;
        $this->_httpRequestClient = HttpRequestClient::getInstance($this->_authCredential);
    }

    public function getAllPaymentProviders()
    {
        $paymentProvidersResponse = $this->_httpRequestClient
                                         ->executePaymentProvidersRequest();
        return $paymentProvidersResponse;
    }
}