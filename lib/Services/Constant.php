<?php

namespace XentePaymentSDK\Services;

class Constant 
{
    // Variable URLs
    public $baseUrl;
    private $productionUrl = 'https://payments.xente.co/api/v1';
    private $sandboxUrl = 'http://34.90.206.233:83/api/v1';
    public $authUrl;
    public $transactionUrl;
    public $accountUrl;
    public $paymentProvidersUrl;

    public function __construct($isProduction)
    {
        if ($isProduction == true) {
            $this->baseUrl = $this->productionUrl;
        }else{
            $this->baseUrl = $this->sandboxUrl;
        }

        $this->authUrl = $this->baseUrl . '/Auth/login';

        $this->transactionUrl = $this->baseUrl . '/transactions';

        $this->accountUrl = $this->baseUrl . '/Accounts';

        $this->paymentProvidersUrl = $this->baseUrl . '/paymentproviders';
    }
}

