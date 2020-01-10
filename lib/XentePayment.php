<?php

namespace XentePaymentSDK;

use XentePaymentSDK\Components\Transaction;
use XentePaymentSDK\Components\Account;
use XentePaymentSDK\Components\PaymentProvider;
use XentePaymentSDK\Services\Validation;
use Exception;

class XentePayment
{
    private $_authCredential;

    // Components of the payment SDK
    public $transactions;
    public $accounts;
    public $paymentProviders;

    public function __construct($apiKey, $password, $mode)
    {
        if (!$apiKey || !$password || !$mode)
        {
            throw new Exception('Please fill in your apikey, password and mode correctly!!');
        } elseif ($mode != 'production' && mode != 'sandbox'){
            throw new Exception('Mode must be production or sandbox!!');
        }else{
            // Initialize the private property
            $this->_authCredential = [ 'apikey' => $apiKey,
                'password' => $password,
                'mode' => $mode
            ];
            // Initialize the components by passing $authCredential in the constructor
            $this->transactions = new Transaction($this->_authCredential);
            $this->accounts = new Account($this->_authCredential);
            $this->paymentProviders = new Account($this->_authCredential);
        }
    }
}