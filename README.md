# Xente Payment SDK for PHP

## Installation
You can install via Composer, run the following command:

```
composer require xente/xentepayment-php-sdk
```

## Usage

To write an application using the SDK

- Register for a developer account and get your apikey at [Xente Developer Portal](http://sandbox.developers.xente.co/).

- After installing, you need to require Composer's autoloader

  ```
  require_once('vendor/autoload.php');
  ```

- Bring in XentePayment class from the XentePaymentSDK namespace

  ```
  use XentePaymentSDK\XentePayment;
  ```

- Create authentication credential, with parameters (apikey, password and mode).

- Create the authentication credential parameters

```
  $apikey = '6A19EA2A706041A599375CC95FF08809';
  $password = 'Demo123456';
  $mode = 'sandbox'; // 'production' 
```

- Initialized XentePayment class with authentication credential above

```
  $xentePaymentGateway = new XentePayment($apikey, $password, $mode);
```

- Create transaction request associative array, metadata is optional parameter, customerReference is the phone number
which transaction is initiated on


```
  $transactionRequest = [
                          'paymentProvider' => 'MTNMOBILEMONEYUG',
                          'amount' => '50000',
                          'message' => 'Web Development Ebook',
                          'customerId' => 'string',
                          'customerPhone' => '256757476805',
                          'customerEmail' => 'customer1@gmail.com',
                          'customerReference' => '256782872845',
                          'batchId' => 'batch001',
                          'requestId' => md5(time()),
                          'metadata' => 'Extra information about the transaction'
                        ];
```

- Create a transaction with the above transaction request

```
 $transactionProcessingResponse = $xentePaymentGateway
                                  ->transactions
                                  ->createTransaction($transactionRequest);
 
 print_r($transactionProcessingResponse);

```

- Get Transaction Details with a specific Transaction ID

```
$transactionId = '9F38AB020C394EA5BC642C25A5CB16BF-256784378515';

$transactionDetailsResponse = $xentePaymentGateway
                             ->transactions
                             ->getTransactionDetailsById($transactionId);
print_r($transactionDetailsResponse);

```

- Get Transaction Details using request ID
```
$transactionDetailsResponse2 = $xentePaymentGateway
                                ->transactions
                                ->getTransactionDetailsByRequestId("4a651febdb515cd6ba02b00ab3c6dd61");
print_r($transactionDetailsResponse2);
```

- Get Account Details by the Account ID

```
  $accountId = '256784378515';
  $accountDetailsResponse = xentePaymentGateway
                            ->accounts
                            ->getAcountDetailsById($accountId);

  print_r($accountDetailsResponse);
```

- List all Payment providers

```
$paymentProvidersResponse = $xentePaymentGateway
                            ->paymentProviders
                            ->getAllPaymentProviders();
print_r($paymentProvidersResponse);

```

## Contributions

- If you would like to contribute, please fork the repo and send in a pull request.

### Refactory Team Xentes
> 1. Olive Nakiyemba
> 2. Kintu Declan Trevor
> 3. Oketayot Julius Peter
