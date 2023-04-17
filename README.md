#### AirtelMoneyPhpSdk
This is a PHP library for integrating with the Airtel Money Africa API.

### Requirements

    PHP 7.4 or higher
    GuzzleHttp PHP library
    PHP dotenv library

### Installation
You can install the package via composer:

composer require thulani/airtel-money-php-sdk

### Usage

## Authorisation
Before you can make any API calls, you need to authorize your app:

$airtelMoney->authorize();

By default, this method will use the client ID and client secret provided when creating the AirtelMoney instance to obtain an access token using the client credentials grant. If you already have an access token, you can pass it as an argument:

$airtelMoney->authorize('your-access-token');

You can also provide a callback function that will be called after the access token is obtained:

$airtelMoney->authorize(null, function ($token) {
    // Do something with the token
});


## AirtelCollection Class

The AirtelCollection class provides methods for initiating a push request, requesting a refund, querying transaction status and processing reconciliations.

# Initialize the class

use Thulani\AirtelMoneyPhpSdk\AirtelCollection;

$config = [
    'client_id' => 'your-client-id',
    'client_secret' => 'your-client-secret',
    'public_key' => 'your-public-key',
    'currency' => 'your-currency-code',
    'country' => 'your-country-code',
    'env' => 'staging' // or 'production'
];

$airtel = new AirtelCollection($config);

# Initiate USSD Push


initiateUssdPush($amount, $phoneNumber, $transactionId = null, $transactionRef = null, $currency = null, $country = null, $callback = null)

This method initiates an USSD push request.

$amount = 50;
$phoneNumber = '712345678';
$transactionRef = 'trx-ref-1234'; // optional
$transactionId = 'trx-id-1234'; // optional
$currency = 'ZMW'; // optional
$country = 'ZM'; // optional

$result = $airtel->initiateUssdPush($amount, $phoneNumber, $transactionId, $transactionRef, $currency, $country);

// If you want to pass in a callback function
$airtel->initiateUssdPush($amount, $phoneNumber, $transactionId, $transactionRef, $currency, $country, function($result) {
    // Handle response
});

# Request Refund

try {
    $response = $airtelCollection->requestRefund(
        $transactionId,
        $country,
        $currency,
        function ($result) {
            // Do something with the result
        }
    );
} catch (\Exception $e) {
    // Handle error
}

# Query transaction Status

try {
    $response = $airtelCollection->queryTransactionStatus(
        $transactionId,
        $country,
        $currency,
        function ($result) {
            // Do something with the result
        }
    );
} catch (\Exception $e) {
    // Handle error
}

# Process Reconcilliation

$payload = '{"transaction": {"status": "SUCCESS", "transaction_reference": "abc123", "transaction_id": "12345", "msisdn": "26077XXXXXXX", "amount": "5000", "currency": "ZMW", "channel": "AIRTELMM", "narrative": "Payment for goods"}}';

$result = $airtelCollection->processReconciliation($payload, function ($transaction) {
    // Do something with the transaction
});

## Airtel Disbursement

use Thulani\AirtelMoneyPhpSdk\AirtelDisbursement;

// Your Airtel API credentials and other configuration options
$config = [
    'client_id' => 'your_client_id',
    'client_secret' => 'your_client_secret',
    'public_key' => 'your_public_key',
    'currency' => 'ZMW',
    'country' => 'ZM',
    'env' => 'production',
];

// Initialize the AirtelDisbursement object with the config options
$airtelDisbursement = new AirtelDisbursement($config);

// Authorize using the authorize() method inherited from the AirtelService class
$airtelDisbursement->authorize();

// Call the initiateDisbursement() method
$phoneNumber = '2547xxxxxxxx';
$amount = 100;
$transactionRef = 'ABC123';
$result = $airtelDisbursement->initiateDisbursement($phoneNumber, $amount, $transactionRef);

// Use the $result as needed



## CallBacks
You can pass a callback function to any method to handle the response data.

function handleResponse($result) {
    // handle result data
}

$result = $collection->initiateUssdPush($amount, $phoneNumber, $transactionId, $transactionRef, $currency, $country, 'handleResponse');

### Licence

The MIT License (MIT). Please see License File for more information.

