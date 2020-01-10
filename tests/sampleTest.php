<?php
require_once('vendor/autoload.php');

use XentePaymentSDK\XentePayment;

$apikey = '6A19EA2A706041A599375CC95FF08809';
$password = 'Demo123456';
$mode = 'sandbox';
$accountId = "256784378515";

$xentePaymentGateway = new XentePayment($apikey, $password, $mode);

$transactionRequest = [
    'paymentProvider' => 'MTNMOBILEMONEYUG',
    'amount' => '1000',
    'message' => 'Web Development Ebook',
    'customerId' => 'string',
    'customerPhone' => '256782872845',
    'customerEmail' => 'juliuspetero@outlook.com',
    'customerReference' => '256782872845',
    'batchId' => 'batch001',
    'requestId' => md5(time()),
    'metadata' => 'Extra information about the transaction'
];

$transactionProcessingResponse = $xentePaymentGateway
    ->transactions
    ->createTransaction($transactionRequest);

print_r($transactionProcessingResponse);


$transactionDetailsResponse = $xentePaymentGateway
    ->transactions
    ->getTransactionDetailsById("30796CADCF45438881F9F9907FA3C3A2-256784378515");
print_r($transactionDetailsResponse);

$transactionDetailsResponse2 = $xentePaymentGateway
    ->transactions
    ->getTransactionDetailsByRequestId("4a651febdb515cd6ba02b00ab3c6dd61");
print_r($transactionDetailsResponse2);

$accountDetailsResponse = $xentePaymentGateway
    ->accounts
    ->getAccountDetailsById($accountId);
print_r($accountDetailsResponse);

$paymentProvidersResponse = $xentePaymentGateway
    ->paymentProviders
    ->getAllPaymentProviders();
print_r($paymentProvidersResponse);









